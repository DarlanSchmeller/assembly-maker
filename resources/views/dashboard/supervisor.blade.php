@extends('layouts.app')
@section('title', 'Torre de Controle | Supervisor')

@section('content')
<div class="min-h-screen bg-slate-50 flex">
    @include('layouts.sidebar')

    <!-- Main Content Area -->
    <div id="main-app-content" class="flex-1 flex flex-col min-w-0 transition-all duration-300">
        <!-- Top App Bar -->
        <header class="h-20 w-full bg-white border-b border-slate-200/80 px-8 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <div class="flex items-center gap-4">
                <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                    <span>Supervisor</span>
                    <span class="text-slate-300">/</span>
                    <span class="text-purple-600 font-bold">Torre de Controle Geral</span>
                </nav>
            </div>
            <div class="flex items-center gap-4">
                <div id="connection-status" class="text-xs bg-emerald-500 text-white px-3 py-1.5 rounded-full font-bold shadow-sm shadow-emerald-500/20 flex items-center gap-1.5">
                    <span class="h-1.5 w-1.5 rounded-full bg-white animate-pulse"></span>
                    <span>Reverb Online</span>
                </div>
                <a href="{{ route('supervisor.analytics') }}" class="text-xs bg-purple-50 text-purple-700 hover:bg-purple-100 font-bold px-3 py-1.5 rounded-lg transition flex items-center gap-1.5 border border-purple-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Ver Análise por Bancada & Erros &rarr;</span>
                </a>
            </div>
        </header>

        <main class="p-8 max-w-7xl w-full mx-auto space-y-6">

            {{-- KPI Row --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="soft-card p-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Peças Produzidas</p>
                    <p id="kpi-pecas" class="text-4xl font-black text-slate-900 mt-2">0</p>
                </div>
                <div class="soft-card p-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Aprovadas</p>
                    <div class="flex items-baseline gap-2 mt-2">
                        <p id="kpi-aprovadas" class="text-4xl font-black text-slate-900">0</p>
                        <span id="kpi-taxa" class="text-sm font-bold text-emerald-600">(0%)</span>
                    </div>
                </div>
                <div class="soft-card p-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Reprovadas</p>
                    <p id="kpi-reprovadas" class="text-4xl font-black text-rose-600 mt-2">0</p>
                </div>
                <div class="soft-card p-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Tempo Médio</p>
                    <p id="kpi-tempo" class="text-4xl font-black text-slate-900 mt-2">0s</p>
                </div>
            </div>

            {{-- Main Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Station Status + Simulation --}}
                <div class="soft-card p-6 space-y-4">
                    <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        Status das Bancadas
                    </h3>
                    <div id="station-list" class="space-y-3">
                        <div class="animate-pulse space-y-2">
                            <div class="h-4 bg-slate-100 rounded w-3/4"></div>
                            <div class="h-4 bg-slate-100 rounded"></div>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-slate-100">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Controles de Simulação</p>
                        <div class="flex gap-2">
                            <button id="btn-sim-success" class="flex-1 text-xs bg-slate-50 hover:bg-emerald-50 text-slate-600 hover:text-emerald-800 font-semibold py-2 px-3 rounded-xl border border-slate-200 hover:border-emerald-200 transition">Simular Aprovado</button>
                            <button id="btn-sim-fail" class="flex-1 text-xs bg-slate-50 hover:bg-rose-50 text-slate-600 hover:text-rose-800 font-semibold py-2 px-3 rounded-xl border border-slate-200 hover:border-rose-200 transition">Simular Reprovado</button>
                        </div>
                    </div>
                </div>

                {{-- Charts + Event Log --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="soft-card p-6">
                        <h3 class="font-bold text-slate-800 text-base mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                            Histórico de Ciclo
                        </h3>
                        <div class="h-48"><canvas id="cycleChart"></canvas></div>
                    </div>
                    <div class="soft-card overflow-hidden">
                        <div class="bg-slate-900 px-5 py-3 flex items-center gap-2" style="border-radius: 20px 20px 0 0">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <h3 class="font-bold text-slate-200 text-sm">Log de Eventos (Tempo Real)</h3>
                        </div>
                        <div id="event-log" class="bg-slate-950 text-emerald-400 font-mono text-xs p-4 h-48 overflow-y-auto space-y-1" style="border-radius: 0 0 20px 20px"></div>
                    </div>
                </div>

            </div>
        </main>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const connStatus = document.getElementById('connection-status');
        const logDiv = document.getElementById('event-log');
        
        // Setup Chart
        const ctx = document.getElementById('cycleChart').getContext('2d');
        const cycleChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [], 
                datasets: [
                    {
                        label: 'Tempo de Ciclo Real (s)',
                        data: [],
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.12)',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#2563eb',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.35,
                        fill: true
                    },
                    {
                        label: 'Meta Máxima (60s)',
                        data: [60, 60, 60],
                        borderColor: '#ef4444',
                        borderWidth: 2,
                        borderDash: [6, 4],
                        pointRadius: 0,
                        fill: false
                    }
                ]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                plugins: { 
                    legend: { 
                        display: true,
                        position: 'top',
                        labels: { boxWidth: 12, font: { size: 11, weight: 'bold' } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y + 's';
                            }
                        }
                    }
                },
                scales: { 
                    y: { 
                        beginAtZero: true,
                        suggestedMax: 80,
                        title: { display: true, text: 'Segundos (s)' },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
        
        // Fetch Initial State
        function refreshState() {
            fetch('/api/supervisor/kpis').then(r => r.json()).then(data => {
                document.getElementById('kpi-pecas').innerText = data.completed;
                document.getElementById('kpi-aprovadas').innerText = data.accepted;
                document.getElementById('kpi-reprovadas').innerText = data.rejected;
                document.getElementById('kpi-taxa').innerText = `(${data.success_rate}%)`;
                document.getElementById('kpi-tempo').innerText = data.avg_cycle_time + 's';
            });
            
            fetch('/api/supervisor/stations').then(r => r.json()).then(stations => {
                const list = document.getElementById('station-list');
                list.innerHTML = '';
                stations.forEach(st => {
                    const isOnline = st.status === 'online';
                    const statusColor = isOnline ? 'bg-emerald-500' : (st.status === 'error' ? 'bg-rose-500' : 'bg-gray-400');
                    const opName = st.operators && st.operators.length > 0 ? st.operators[0].name : 'Nenhum operador';
                    list.innerHTML += `
                        <div class="p-3.5 border border-gray-200 rounded-lg flex items-center justify-between transition hover:border-blue-300" id="station-${st.code}">
                            <div>
                                <div class="font-bold text-gray-800 text-sm">${st.name}</div>
                                <div class="text-xs text-gray-500 mt-0.5">Operador: <span class="font-medium text-gray-700">${opName}</span></div>
                                <div class="text-xs text-blue-600 font-mono mt-1" id="reading-${st.code}">Último peso: --</div>
                            </div>
                            <div class="flex flex-col items-end gap-1">
                                <span class="flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider ${isOnline ? 'text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full' : 'text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full'}" id="badge-${st.code}">
                                    <span class="flex h-2 w-2 relative">
                                      ${isOnline ? '<span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>' : ''}
                                      <span class="relative inline-flex rounded-full h-2 w-2 ${statusColor}"></span>
                                    </span>
                                    <span id="status-${st.code}">${st.status}</span>
                                </span>
                            </div>
                        </div>
                    `;
                });
            });
        }
        
        refreshState();
        
        function logEvent(msg, type = 'info') {
            const timeString = new Date().toLocaleTimeString();
            const color = type === 'error' ? 'text-red-400' : (type === 'warn' ? 'text-yellow-400' : 'text-green-400');
            logDiv.innerHTML += `<div><span class="text-gray-500">[${timeString}]</span> <span class="${color}">${msg}</span></div>`;
            logDiv.scrollTop = logDiv.scrollHeight;
        }

        // Websocket bindings
        window.Echo.connector.pusher.connection.bind('connected', () => {
            connStatus.classList.replace('bg-red-500', 'bg-green-500');
            connStatus.innerText = 'Reverb Online';
            logEvent('Conectado ao Websocket (Reverb)');
        });
        window.Echo.connector.pusher.connection.bind('disconnected', () => {
            connStatus.classList.replace('bg-green-500', 'bg-red-500');
            connStatus.innerText = 'Reverb Offline';
            logEvent('Desconectado do Websocket', 'error');
        });

        // Listen to global events
        window.Echo.channel('production')
            .listen('StationStatusChanged', (e) => {
                logEvent(`Status da Bancada alterado: ${e.station_code} agora está ${e.status.toUpperCase()} (${e.operator_name || 'Desconectado'})`, e.status === 'online' ? 'info' : 'warn');
                refreshState();
            })
            .listen('TelemetryReceived', (e) => {
                logEvent(`Telemetria: ${e.station_code} | Peso: ${e.reading.value}g`);
                const weightDiv = document.getElementById(`reading-${e.station_code}`);
                if (weightDiv) weightDiv.innerText = `Último peso: ${e.reading.value}g`;
                
                if (e.validation) {
                    refreshState(); // Refresh KPIs if there was a validation
                    if (e.validation.result === 'approved') {
                        logEvent(`Validação ${e.station_code}: APROVADO (Desvio: ${e.validation.deviation}g)`, 'info');
                    } else {
                        logEvent(`ALERTA [${e.station_code}]: NÃO-CONFORMIDADE! Peso ${e.reading.value}g fora da tolerância.`, 'error');
                    }
                }
            })
            .listen('ProductionCompleted', (e) => {
                logEvent(`Produção #${e.production.id} concluída. Tempo: ${e.time}s`);
                refreshState();
                
                // Update chart
                cycleChart.data.labels.push('#' + e.production.id);
                cycleChart.data.datasets[0].data.push(e.time);
                cycleChart.data.datasets[1].data.push(60);
                if(cycleChart.data.labels.length > 12) {
                    cycleChart.data.labels.shift();
                    cycleChart.data.datasets[0].data.shift();
                    cycleChart.data.datasets[1].data.shift();
                }
                cycleChart.update();
            });

        // Simulation buttons
        document.getElementById('btn-sim-success').addEventListener('click', () => {
            fetch('/api/simulate/telemetry', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ station: 'bancada_01', weight: 101.5 })
            });
            logEvent('Enviando simulação: Aprovado...', 'warn');
        });
        document.getElementById('btn-sim-fail').addEventListener('click', () => {
            fetch('/api/simulate/telemetry', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ station: 'bancada_01', weight: 85.0 })
            });
            logEvent('Enviando simulação: Reprovado...', 'warn');
        });
    });
</script>
@endpush