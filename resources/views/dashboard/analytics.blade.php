@extends('layouts.app')
@section('title', 'Análise por Bancada | Torre de Controle')

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
                    <span class="text-purple-600 font-bold">Análise por Bancada</span>
                </nav>
                <div id="connection-status" class="text-xs bg-red-500 text-white px-3 py-1.5 rounded-full font-bold shadow-sm flex items-center gap-1.5">
                    <span class="h-1.5 w-1.5 rounded-full bg-white animate-pulse"></span>
                    <span>Reverb Offline</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('supervisor.dashboard') }}" class="text-xs text-slate-500 hover:text-slate-800 font-semibold transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Torre de Controle
                </a>
                <button id="btn-refresh" class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold px-4 py-2 rounded-lg text-xs shadow-sm transition flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Atualizar Dados
                </button>
            </div>
        </header>

        <main class="flex-1 p-8 max-w-7xl w-full mx-auto space-y-6">

            <!-- Page Title -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-800">Desempenho Individual de Postos de Trabalho</h1>
                    <p class="text-gray-500 text-sm mt-1">Monitoramento de erros de montagem, peças corretas, tempo de ciclo e alertas ativos por operador.</p>
                </div>
            </div>

            <!-- Filter Checkboxes -->
            <div id="workbench-filters" class="flex flex-wrap gap-3"></div>

            <!-- Per-Workbench Cards (populated by JS) -->
            <div id="workbench-cards" class="grid grid-cols-1 md:grid-cols-2 gap-6"></div>

            <!-- Comparative Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="soft-card p-6">
                    <h3 class="font-bold text-slate-800 text-base mb-1 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Comparativo de Aprovadas vs. Erros (Por Bancada)
                    </h3>
                    <p class="text-xs text-slate-400 mb-4">Volume de peças montadas corretamente vs peças rejeitadas por posto de montagem.</p>
                    <div class="h-64 relative">
                        <canvas id="qualityComparisonChart"></canvas>
                    </div>
                </div>

                <div class="soft-card p-6">
                    <h3 class="font-bold text-slate-800 text-base mb-1 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Tempo Médio de Ciclo vs. Meta (60s)
                    </h3>
                    <p class="text-xs text-slate-400 mb-4">Lead time médio de montagem comparado à meta de engenharia de ciclo.</p>
                    <div class="h-64 relative">
                        <canvas id="leadTimeComparisonChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Real-time Alerts Table -->
            <div class="soft-card overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                            <span class="flex h-3 w-3 rounded-full bg-rose-500 animate-ping"></span>
                            Histórico de Não-Conformidades e Erros de Montagem
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">Identificação exata de qual bancada, operador e tipo de erro foi gerado.</p>
                    </div>
                    <span class="text-xs font-semibold bg-rose-50 text-rose-700 px-3 py-1 rounded-full border border-rose-100">Alertas Ativos de Qualidade</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-500 border-b border-slate-100">
                            <tr>
                                <th class="py-3 px-6">Horário</th>
                                <th class="py-3 px-6">Bancada / Posto</th>
                                <th class="py-3 px-6">Tipo do Erro</th>
                                <th class="py-3 px-6">Detalhes / Desvio</th>
                                <th class="py-3 px-6">Severidade</th>
                            </tr>
                        </thead>
                        <tbody id="alerts-tbody" class="divide-y divide-slate-50 font-mono text-xs">
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-400 font-sans">Nenhum erro registrado recentemente.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div><!-- end main-app-content -->
</div><!-- end flex wrapper -->
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let qualityChart = null;
        let leadTimeChart = null;
        let currentStations = [];

        // ─── Reverb connection indicator ───────────────────────────────────────
        const connStatus = document.getElementById('connection-status');
        if (window.Echo) {
            window.Echo.connector.pusher.connection.bind('connected', () => {
                connStatus.className = 'text-xs bg-emerald-500 text-white px-3 py-1.5 rounded-full font-bold shadow-sm flex items-center gap-1.5';
                connStatus.innerHTML = '<span class="h-1.5 w-1.5 rounded-full bg-white animate-pulse"></span><span>Reverb Online</span>';
            });
            window.Echo.connector.pusher.connection.bind('disconnected', () => {
                connStatus.className = 'text-xs bg-red-500 text-white px-3 py-1.5 rounded-full font-bold shadow-sm flex items-center gap-1.5';
                connStatus.innerHTML = '<span class="h-1.5 w-1.5 rounded-full bg-white"></span><span>Reverb Offline</span>';
            });
        }

        // ─── Chart initialisation ──────────────────────────────────────────────
        function initCharts() {
            const ctxQuality = document.getElementById('qualityComparisonChart').getContext('2d');
            qualityChart = new Chart(ctxQuality, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [
                        { label: 'Aprovadas',         data: [], backgroundColor: '#10b981' },
                        { label: 'Erros / Reprovadas', data: [], backgroundColor: '#ef4444' }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11, weight: 'bold' } } } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } }
                }
            });

            const ctxLead = document.getElementById('leadTimeComparisonChart').getContext('2d');
            leadTimeChart = new Chart(ctxLead, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [
                        {
                            label: 'Tempo Médio (s)',
                            data: [],
                            backgroundColor: '#6366f1',
                            order: 2
                        },
                        {
                            label: 'Meta de Ciclo (s)',
                            data: [],
                            type: 'line',
                            borderColor: '#ef4444',
                            borderWidth: 2,
                            borderDash: [6, 4],
                            pointRadius: 0,
                            fill: false,
                            order: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11, weight: 'bold' } } } },
                    scales: { y: { beginAtZero: true, suggestedMax: 90, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } }
                }
            });
        }

        // ─── Filter checkboxes ─────────────────────────────────────────────────
        function renderFilters(stations) {
            const container = document.getElementById('workbench-filters');
            container.innerHTML = '';
            stations.forEach(st => {
                container.innerHTML += `
                    <label class="flex items-center gap-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 px-3 py-1.5 rounded-lg cursor-pointer hover:border-purple-300 transition">
                        <input type="checkbox" class="filter-checkbox accent-purple-600" data-id="${st.id}" checked>
                        ${st.name.split(' (')[0]}
                    </label>`;
            });
        }

        // ─── Workbench summary cards ───────────────────────────────────────────
        function renderWorkbenchCards(stations) {
            const container = document.getElementById('workbench-cards');
            container.innerHTML = '';

            if (!stations || stations.length === 0) {
                container.innerHTML = '<p class="text-gray-400 text-sm col-span-2 text-center py-8">Nenhuma bancada encontrada.</p>';
                return;
            }

            stations.forEach(st => {
                const isOnline = st.status === 'online';
                const statusBadge = isOnline
                    ? '<span class="bg-emerald-100 text-emerald-800 font-bold px-2.5 py-1 rounded-full text-xs flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-emerald-500"></span>ONLINE</span>'
                    : '<span class="bg-gray-100 text-gray-600 font-bold px-2.5 py-1 rounded-full text-xs flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-gray-400"></span>OFFLINE</span>';

                const alertNotice = st.active_alerts > 0
                    ? `<div class="mt-4 p-3 bg-rose-50 border border-rose-200 rounded-lg text-xs text-rose-800 flex items-start gap-2">
                         <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                         <div><strong>Último Erro:</strong> ${st.last_alert ? st.last_alert.title : 'Desvio detectado'}<div class="text-rose-600 mt-0.5">${st.last_alert ? st.last_alert.description : ''} (${st.last_alert ? st.last_alert.created_at : ''})</div></div>
                       </div>`
                    : `<div class="mt-4 p-3 bg-emerald-50 border border-emerald-200 rounded-lg text-xs text-emerald-800 flex items-center gap-2">
                         <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                         Nenhum alerta pendente nesta bancada.
                       </div>`;

                container.innerHTML += `
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">${st.name}</h3>
                                <p class="text-xs text-gray-500">Operador: <span class="font-semibold text-gray-700">${st.operator}</span></p>
                            </div>
                            ${statusBadge}
                        </div>
                        <div class="grid grid-cols-4 gap-2 py-3 border-y border-gray-100 text-center">
                            <div><div class="text-xs text-gray-400 font-bold uppercase">Total</div><div class="text-lg font-black text-gray-800">${st.total_pieces}</div></div>
                            <div><div class="text-xs text-emerald-600 font-bold uppercase">Acertos</div><div class="text-lg font-black text-emerald-600">${st.approved}</div></div>
                            <div><div class="text-xs text-rose-600 font-bold uppercase">Erros</div><div class="text-lg font-black text-rose-600">${st.rejected}</div></div>
                            <div><div class="text-xs text-blue-600 font-bold uppercase">Qualidade</div><div class="text-lg font-black text-blue-600">${st.success_rate}%</div></div>
                        </div>
                        ${alertNotice}
                    </div>`;
            });
        }

        // ─── Chart data render ─────────────────────────────────────────────────
        function renderCharts(stations) {
            const checkedIds = Array.from(document.querySelectorAll('.filter-checkbox:checked')).map(cb => cb.dataset.id);
            const filtered = stations.filter(s => checkedIds.includes(String(s.id)));

            const labels   = filtered.map(s => s.name.split(' (')[0]);
            const approved = filtered.map(s => s.approved);
            const rejected = filtered.map(s => s.rejected);
            const avgTime  = filtered.map(s => s.avg_cycle_time);
            const targets  = filtered.map(() => 60);

            qualityChart.data.labels = labels;
            qualityChart.data.datasets[0].data = approved;
            qualityChart.data.datasets[1].data = rejected;
            qualityChart.update();

            leadTimeChart.data.labels = labels;
            leadTimeChart.data.datasets[0].data = avgTime;
            leadTimeChart.data.datasets[1].data = targets;
            leadTimeChart.update();
        }

        // ─── Alerts table ──────────────────────────────────────────────────────
        function renderAlertsTable(alerts) {
            const tbody = document.getElementById('alerts-tbody');
            if (!alerts || alerts.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="py-6 text-center text-gray-400 font-sans">Nenhum erro registrado recentemente.</td></tr>';
                return;
            }
            tbody.innerHTML = '';
            alerts.forEach(a => {
                const sevBadge = a.severity === 'critical'
                    ? '<span class="bg-rose-100 text-rose-800 font-bold px-2 py-0.5 rounded text-xs">Crítico</span>'
                    : '<span class="bg-amber-100 text-amber-800 font-bold px-2 py-0.5 rounded text-xs">Alerta</span>';
                tbody.innerHTML += `
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-6 text-gray-500 font-mono">${a.time}</td>
                        <td class="py-3 px-6 font-bold text-gray-800 font-sans">${a.station_name}</td>
                        <td class="py-3 px-6 text-rose-600 font-sans font-semibold">${a.title}</td>
                        <td class="py-3 px-6 text-gray-600 font-sans">${a.description}</td>
                        <td class="py-3 px-6">${sevBadge}</td>
                    </tr>`;
            });
        }

        // ─── Main data loader ──────────────────────────────────────────────────

        async function loadAnalytics() {
            try {
                const response = await fetch('/api/supervisor/workbenches-data');

                if (!response.ok) {
                    const errorBody = await response.text();

                    console.error('Workbenches API error:', {
                        status: response.status,
                        body: errorBody
                    });

                    throw new Error(
                        `Failed to load workbenches: HTTP ${response.status}`
                    );
                }

                const data = await response.json();

                // The API must return an array for the current rendering functions.
                if (!Array.isArray(data)) {
                    console.error('Unexpected workbenches response:', data);
                    throw new Error('Expected an array of workbenches.');
                }

                currentStations = data;

                renderFilters(currentStations);
                renderWorkbenchCards(currentStations);
                renderCharts(currentStations);

            } catch (err) {
                console.error('Error loading workbenches data:', err);
            }

            // Load alerts independently, even if workbenches fail.
            try {
                const response = await fetch('/api/supervisor/recent-alerts');

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const alerts = await response.json();

                if (!Array.isArray(alerts)) {
                    throw new Error('Expected an array of alerts.');
                }

                renderAlertsTable(alerts);

            } catch (err) {
                console.error('Error loading alerts:', err);
            }
        }


        // ─── Bootstrap ────────────────────────────────────────────────────────
        initCharts();
        loadAnalytics();

        document.getElementById('btn-refresh').addEventListener('click', loadAnalytics);

        document.getElementById('workbench-filters').addEventListener('change', function (e) {
            if (e.target && e.target.matches('.filter-checkbox')) {
                renderCharts(currentStations);
            }
        });

        // Real-time Reverb sync
        if (window.Echo) {
            window.Echo.channel('production')
                .listen('TelemetryReceived',    () => loadAnalytics())
                .listen('ProductionCompleted',   () => loadAnalytics())
                .listen('StationStatusChanged',  () => loadAnalytics());
        }
    });
</script>
@endpush
