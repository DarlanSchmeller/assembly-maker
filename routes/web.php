<?php

use App\Events\StationStatusChanged;
use App\Models\Step;
use App\Models\Workstation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login.form');
});

Route::get('/login', function () {
    return view('login');
})->name('login.form');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        $user = Auth::user();

        if ($user->role === 'operator' && $user->workstation_id) {
            $station = Workstation::find($user->workstation_id);
            if ($station) {
                $station->update([
                    'status' => 'online',
                    'last_heartbeat_at' => now(),
                ]);
                event(new StationStatusChanged($station->code, 'online', $user->name));
            }
        }

        if ($user->role === 'supervisor') {
            return redirect()->intended(route('supervisor.dashboard'));
        }

        return redirect()->intended(route('operator.dashboard'));
    }

    return back()->withErrors([
        'email' => 'As credenciais fornecidas não conferem com nossos registros.',
    ])->onlyInput('email');
})->name('login');

Route::post('/logout', function (Request $request) {
    $user = Auth::user();
    if ($user && $user->role === 'operator' && $user->workstation_id) {
        $station = Workstation::find($user->workstation_id);
        if ($station) {
            $station->update(['status' => 'offline']);
            event(new StationStatusChanged($station->code, 'offline', null));
        }
    }

    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login.form');
})->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::middleware('role:operator')->group(function () {
        Route::get('/operator', function () {
            $user = Auth::user();
            $station = $user->workstation ?? Workstation::first();
            $step = $station ? Step::where('workstation_id', $station->id)->first() : null;

            return view('dashboard.operator', compact('station', 'step'));
        })->name('operator.dashboard');

        Route::get('/operator/instructions', function () {
            $user = Auth::user();
            $station = $user->workstation ?? Workstation::first();
            $steps = Step::where('production_line_id', $station->production_line_id ?? 1)
                ->orderBy('order')
                ->get();

            return view('dashboard.instructions', compact('station', 'steps'));
        })->name('operator.instructions');
    });

    Route::middleware('role:supervisor')->group(function () {
        Route::get('/supervisor', function () {
            return view('dashboard.supervisor');
        })->name('supervisor.dashboard');

        Route::get('/supervisor/analytics', function () {
            return view('dashboard.analytics');
        })->name('supervisor.analytics');
    });
});
