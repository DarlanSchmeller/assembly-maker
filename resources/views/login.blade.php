@extends('layouts.app')
@section('title', 'Login - Portal IIoT')

@section('content')
<div class="relative min-h-screen w-full flex items-center justify-center p-4 bg-slate-950 overflow-hidden">

    <!-- Factory Hero Background -->
    <div class="absolute inset-0 z-0">
        <img
            src="{{ asset('images/factory_bg.jpg') }}"
            alt="Renault Smart Factory"
            class="w-full h-full object-cover opacity-35 scale-105 filter blur-[1px]"
        >
        <div class="absolute inset-0 bg-gradient-to-tr from-slate-950 via-slate-900/80 to-purple-950/40 mix-blend-multiply"></div>
    </div>

    <!-- Centered Login Card -->
    <div class="relative z-10 w-full max-w-md">

        <!-- Logo & Brand Header -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-purple-600 to-indigo-500 shadow-xl shadow-purple-500/25 mb-3 border border-white/20 overflow-hidden">
                <img
                    src="{{ asset('images/renault_logo.png') }}"
                    alt="Renault logo"
                    class="w-full h-full object-contain scale-125"
                >
            </div>

            <h1 class="text-3xl font-extrabold text-white tracking-tight">
                Renault <span class="text-purple-400">IIoT</span>
            </h1>

            <p class="text-slate-300 text-sm mt-1">
                Smart Assembly Line &amp; Quality Control
            </p>
        </div>

        <!-- Login Form Card -->
        <div class="bg-white/95 backdrop-blur-xl p-8 rounded-3xl shadow-2xl border border-white/60">

            <!-- Validation Errors -->
            @if($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email -->
                <div>
                    <label for="input-email" class="block text-slate-700 text-xs font-bold uppercase tracking-wider mb-1.5">
                        Matrícula (E-mail Corporativo)
                    </label>

                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </span>

                        <input
                            type="email"
                            name="email"
                            id="input-email"
                            value="{{ old('email', 'supervisor@renault.com') }}"
                            class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-purple-600 focus:bg-white transition"
                            required
                            autofocus
                            placeholder="nome@renault.com"
                        >
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="input-password" class="block text-slate-700 text-xs font-bold uppercase tracking-wider mb-1.5">
                        Senha de Acesso
                    </label>

                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>

                        <input
                            type="password"
                            name="password"
                            id="input-password"
                            value="password"
                            class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-purple-600 focus:bg-white transition"
                            required
                        >
                    </div>
                </div>

                <!-- Submit -->
                <button
                    type="submit"
                    class="w-full mt-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-purple-600/30 hover:shadow-purple-600/40 transition duration-200 flex items-center justify-center gap-2 text-sm"
                >
                    <span>Acessar Posto de Trabalho</span>

                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>

            <!-- Collapsible Demo Profiles -->
            <div class="mt-6 pt-5 border-t border-slate-100">

                <!-- Toggle Button -->
                <button
                    type="button"
                    id="demo-toggle"
                    onclick="toggleDemoProfiles()"
                    aria-expanded="false"
                    aria-controls="demo-profiles"
                    class="w-full flex items-center justify-between gap-3 text-xs font-bold uppercase tracking-wider text-slate-400 hover:text-purple-600 transition"
                >
                    <span>Acesso Rápido para Apresentação</span>

                    <svg
                        id="demo-chevron"
                        class="w-4 h-4 shrink-0 transition-transform duration-200"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m19 9-7 7-7-7"
                        />
                    </svg>
                </button>

                <!-- Hidden Profiles -->
                <div id="demo-profiles" class="hidden mt-3 space-y-2">

                    <!-- Supervisor -->
                    <button
                        type="button"
                        onclick="fillCreds('supervisor@renault.com')"
                        class="group w-full flex items-center justify-between gap-2 p-2.5 rounded-xl border border-slate-100 bg-slate-50 hover:bg-purple-50 hover:border-purple-200 transition text-left"
                    >
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="w-7 h-7 rounded-lg bg-purple-100 text-purple-700 font-bold flex items-center justify-center text-xs shrink-0">
                                SV
                            </span>

                            <div class="min-w-0">
                                <div class="text-xs font-bold text-slate-800 group-hover:text-purple-900">
                                    Torre de Controle (Supervisor)
                                </div>
                                <div class="text-[11px] text-slate-400 font-mono">
                                    supervisor@renault.com
                                </div>
                            </div>
                        </div>

                        <span class="text-xs font-semibold text-purple-600 shrink-0">
                            Usar &rarr;
                        </span>
                    </button>

                    <!-- Operator 1 -->
                    <button
                        type="button"
                        onclick="fillCreds('operador1@renault.com')"
                        class="group w-full flex items-center justify-between gap-2 p-2.5 rounded-xl border border-slate-100 bg-slate-50 hover:bg-blue-50 hover:border-blue-200 transition text-left"
                    >
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="w-7 h-7 rounded-lg bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-xs shrink-0">
                                B1
                            </span>

                            <div class="min-w-0">
                                <div class="text-xs font-bold text-slate-800 group-hover:text-blue-900">
                                    Operador Bancada 01 (Base &amp; Sensor)
                                </div>
                                <div class="text-[11px] text-slate-400 font-mono">
                                    operador1@renault.com
                                </div>
                            </div>
                        </div>

                        <span class="text-xs font-semibold text-blue-600 shrink-0">
                            Usar &rarr;
                        </span>
                    </button>

                    <!-- Operator 2 -->
                    <button
                        type="button"
                        onclick="fillCreds('operador2@renault.com')"
                        class="group w-full flex items-center justify-between gap-2 p-2.5 rounded-xl border border-slate-100 bg-slate-50 hover:bg-emerald-50 hover:border-emerald-200 transition text-left"
                    >
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center text-xs shrink-0">
                                B2
                            </span>

                            <div class="min-w-0">
                                <div class="text-xs font-bold text-slate-800 group-hover:text-emerald-900">
                                    Operador Bancada 02 (Acabamento)
                                </div>
                                <div class="text-[11px] text-slate-400 font-mono">
                                    operador2@renault.com
                                </div>
                            </div>
                        </div>

                        <span class="text-xs font-semibold text-emerald-600 shrink-0">
                            Usar &rarr;
                        </span>
                    </button>

                </div>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-xs text-slate-500 mt-5">
            Renault Industrial IIoT Platform &bull; v2.4.0
        </p>
    </div>
</div>

<script>
    function toggleDemoProfiles() {
        const profiles = document.getElementById('demo-profiles');
        const toggle = document.getElementById('demo-toggle');
        const chevron = document.getElementById('demo-chevron');

        const isHidden = profiles.classList.toggle('hidden');

        toggle.setAttribute('aria-expanded', String(!isHidden));
        chevron.classList.toggle('rotate-180', !isHidden);
    }

    function fillCreds(email) {
        document.getElementById('input-email').value = email;
        document.getElementById('input-password').value = 'password';
    }
</script>
@endsection