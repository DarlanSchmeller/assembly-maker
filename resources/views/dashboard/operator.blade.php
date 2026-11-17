@extends('layouts.app')
@section('title', 'Operador - ' . ($station->name ?? 'Bancada'))

@section('content')
<div class="min-h-screen bg-slate-50 flex">
    @include('layouts.sidebar')

    <!-- Main Content Area -->
    <div id="main-app-content" class="flex-1 flex flex-col min-w-0 transition-all duration-300">
        <!-- Top App Bar -->
        <header class="h-20 bg-white border-b border-slate-200/80 px-8 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <div class="flex items-center gap-4">
                <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                    <span>Linha de Montagem</span>
                    <span class="text-slate-300">/</span>
                    <span class="text-purple-600 font-bold">{{ $station->name ?? 'Bancada 01' }}</span>
                </nav>
            </div>
            <div class="flex items-center gap-3">
                <div id="connection-status" class="text-xs bg-emerald-500 text-white px-3 py-1.5 rounded-full font-bold shadow-sm shadow-emerald-500/20 flex items-center gap-1.5">
                    <span class="h-1.5 w-1.5 rounded-full bg-white animate-pulse"></span>
                    <span>Bancada Conectada</span>
                </div>
            </div>
        </header>

        <main class="p-8 max-w-5xl w-full mx-auto space-y-6">

            {{-- Cycle Control Card --}}
            <div class="soft-card p-6">
                <div class="flex justify-between items-center mb-5">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">{{ $step->name ?? 'Controle de Ciclo de Montagem' }}</h2>
                        <p class="text-sm text-slate-500 mt-0.5">Operador: <span class="font-semibold text-slate-700">{{ Auth::user()->name ?? 'Operador' }}</span> &middot; Meta: <span class="font-semibold text-purple-600">{{ $step->time_cycle_goal ?? 60 }}s</span></p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs uppercase font-bold text-slate-400 block">Tempo de Ciclo</span>
                        <div id="cycle-timer" class="text-3xl font-mono font-bold text-blue-600">00:00</div>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button id="btn-start" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 px-8 rounded-xl text-base flex-1 transition shadow-md shadow-emerald-600/20 flex justify-center items-center gap-2">
                        <svg id="icon-play" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>INICIAR MONTAGEM</span>
                    </button>
                    <button id="btn-finish" disabled class="bg-slate-100 text-slate-400 font-bold py-4 px-6 rounded-xl text-base cursor-not-allowed transition flex justify-center items-center gap-2">
                        <span>CONCLUIR CICLO</span>
                    </button>
                </div>
            </div>

            {{-- IoT Simulator --}}
            <div class="soft-card p-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h4 class="font-bold text-slate-800 flex items-center gap-2 text-sm">
                            <span class="flex h-2.5 w-2.5 rounded-full bg-indigo-500"></span>
                            Simulador de Bancada IoT (Modo Apresentação)
                        </h4>
                        <p class="text-xs text-slate-500 mt-1">Simule o encaixe da peça na balança para demonstrar a validação e envio em tempo real.</p>
                    </div>
                    <div class="flex gap-2 shrink-0">
                        <button id="btn-sim-correct" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold py-2 px-4 rounded-xl shadow-sm transition flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Peça Correta (100g)
                        </button>
                        <button id="btn-sim-wrong" class="bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold py-2 px-4 rounded-xl shadow-sm transition flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Peça Fora de Peso (120g)
                        </button>
                    </div>
                </div>
            </div>

            {{-- Instructions + Weight Display --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 soft-card p-6">
                    <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        Instruções de Trabalho &mdash; Etapa 1: Base IoT
                    </h3>
                    <div class="p-4 bg-purple-50/70 border border-purple-100 rounded-xl space-y-2.5 text-sm text-purple-900">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-purple-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Posicione a base da carcáça plástica sobre a balança.</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-purple-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Encaixe o sensor ultrasssônico e o módulo principal.</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-purple-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Monitore o peso registrado na balança: <strong>100g (tolerância ±5g)</strong>.</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-purple-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Aguarde o status <span class="font-semibold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-lg">APROVADO</span> para retirar e finalizar o ciclo.</span>
                        </div>
                    </div>
                </div>

                <div class="soft-card p-6 text-center flex flex-col justify-center items-center">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Peso Medido na Célula</p>
                    <div id="weight-display" class="text-5xl font-black text-slate-900 transition-all">-- g</div>
                    <div id="weight-status" class="mt-4 px-4 py-1.5 rounded-full text-xs font-bold bg-slate-100 text-slate-500 tracking-wider">AGUARDANDO PEÇA</div>
                    <div id="weight-detail" class="text-xs text-slate-400 mt-2">Faixa esperada: 95g a 105g</div>
                </div>
            </div>

        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const connStatus = document.getElementById('connection-status');
        const weightDisplay = document.getElementById('weight-display');
        const weightStatus = document.getElementById('weight-status');
        const weightDetail = document.getElementById('weight-detail');
        const btnStart = document.getElementById('btn-start');
        const btnFinish = document.getElementById('btn-finish');
        const timerDisplay = document.getElementById('cycle-timer');
        const btnSimCorrect = document.getElementById('btn-sim-correct');
        const btnSimWrong = document.getElementById('btn-sim-wrong');
        
        let timerInterval = null;
        let elapsedSeconds = 0;
        let isRunning = false;

        function formatTime(totalSeconds) {
            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;
            return String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
        }

        function startTimer() {
            if (timerInterval) clearInterval(timerInterval);
            elapsedSeconds = 0;
            isRunning = true;
            timerDisplay.innerText = formatTime(0);
            timerDisplay.classList.remove('text-red-500');
            timerDisplay.classList.add('text-blue-600');
            
            timerInterval = setInterval(() => {
                elapsedSeconds++;
                timerDisplay.innerText = formatTime(elapsedSeconds);
                if (elapsedSeconds > 60) {
                    timerDisplay.classList.remove('text-blue-600');
                    timerDisplay.classList.add('text-red-500');
                }
            }, 1000);

            btnStart.classList.replace('bg-emerald-600', 'bg-amber-500');
            btnStart.classList.replace('hover:bg-emerald-700', 'hover:bg-amber-600');
            btnStart.querySelector('span').innerText = 'EM ANDAMENTO...';
            
            btnFinish.disabled = false;
            btnFinish.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
            btnFinish.classList.add('bg-blue-600', 'hover:bg-blue-700', 'text-white', 'shadow-md');
        }

        function stopTimer() {
            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }
            isRunning = false;
            btnStart.classList.replace('bg-amber-500', 'bg-emerald-600');
            btnStart.classList.replace('hover:bg-amber-600', 'hover:bg-emerald-700');
            btnStart.querySelector('span').innerText = 'INICIAR NOVO CICLO';

            btnFinish.disabled = true;
            btnFinish.className = 'bg-gray-300 text-gray-500 font-bold py-4 px-6 rounded-lg text-lg cursor-not-allowed transition flex justify-center items-center gap-2';
        }

        // Listen to connection
        if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
            window.Echo.connector.pusher.connection.bind('connected', () => {
                connStatus.className = 'text-sm bg-emerald-500 px-3 py-1 rounded-full font-medium';
                connStatus.innerText = 'Online';
            });
            window.Echo.connector.pusher.connection.bind('disconnected', () => {
                connStatus.className = 'text-sm bg-rose-500 px-3 py-1 rounded-full font-medium';
                connStatus.innerText = 'Offline';
            });
            if (window.Echo.connector.pusher.connection.state === 'connected') {
                connStatus.className = 'text-sm bg-emerald-500 px-3 py-1 rounded-full font-medium';
                connStatus.innerText = 'Online';
            }
        }

        function updateWeightDisplay(weight, validation) {
            weightDisplay.innerText = parseFloat(weight).toFixed(1) + ' g';
            if (validation) {
                if (validation.result === 'approved') {
                    weightStatus.className = 'mt-4 px-4 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 animate-pulse';
                    weightStatus.innerText = 'APROVADO (DENTRO DA META)';
                    weightDetail.innerText = 'Desvio: ' + parseFloat(validation.deviation || 0).toFixed(1) + 'g';
                } else {
                    weightStatus.className = 'mt-4 px-4 py-1.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800';
                    weightStatus.innerText = 'REPROVADO (' + (validation.rejection_reason || 'FORA DO PADRÃO') + ')';
                    weightDetail.innerText = 'Desvio: ' + parseFloat(validation.deviation || 0).toFixed(1) + 'g';
                }
            } else {
                weightStatus.className = 'mt-4 px-4 py-1.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800';
                weightStatus.innerText = 'MEDINDO...';
            }
        }

        // Listen for realtime telemetry
        const stationCode = "{{ $station->code ?? 'bancada_01' }}";
        const expectedWeight = {{ $step->expected_weight_grams ?? 100 }};
        const tolerance = {{ $step->weight_tolerance_grams ?? 5 }};

        if (window.Echo) {
            window.Echo.channel('workstation.' + stationCode)
                .listen('TelemetryReceived', (e) => {
                    if (e.reading && e.reading.value !== undefined) {
                        updateWeightDisplay(e.reading.value, e.validation);
                    }
                });
        }

        btnStart.addEventListener('click', function() {
            startTimer();
            fetch('/api/operator/start-production', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ workstation_code: stationCode })
            });
        });

        btnFinish.addEventListener('click', function() {
            stopTimer();
            weightDisplay.innerText = '-- g';
            weightStatus.className = 'mt-4 px-4 py-1.5 rounded-full text-xs font-bold bg-gray-100 text-gray-600';
            weightStatus.innerText = 'CICLO CONCLUÍDO';
            weightDetail.innerText = 'Aguardando início do próximo ciclo';
        });

        function simulateWeight(weight) {
            if (!isRunning) {
                startTimer();
            }
            fetch('/api/simulate/telemetry', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ station: stationCode, weight: weight })
            }).then(r => r.json()).then(data => {
                const dev = Math.abs(weight - expectedWeight);
                const approved = dev <= tolerance;
                updateWeightDisplay(weight, {
                    result: approved ? 'approved' : 'rejected',
                    deviation: dev,
                    rejection_reason: approved ? null : 'Peso fora da tolerância'
                });
            });
        }

        btnSimCorrect.addEventListener('click', () => simulateWeight(expectedWeight));
        btnSimWrong.addEventListener('click', () => simulateWeight(expectedWeight + 20));
    });
</script>
@endpush