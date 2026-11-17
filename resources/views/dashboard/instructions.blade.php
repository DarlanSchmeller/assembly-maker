@extends('layouts.app')
@section('title', 'Instruções de Montagem Ilustradas')

@section('content')
<div class="min-h-screen bg-slate-50 flex">
    @include('layouts.sidebar')

    <!-- Main Content Area -->
    <div id="main-app-content" class="flex-1 flex flex-col transition-all duration-300">
        <!-- Top App Bar -->
        <header class="h-20 bg-white border-b border-slate-200/80 px-8 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <div class="flex items-center gap-4">
                <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                    <span>Montagem</span>
                    <span class="text-slate-300">/</span>
                    <span class="text-purple-600">Guia Visual LEGO</span>
                </nav>
            </div>
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-2 text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-100">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>{{ $station->name ?? 'Bancada' }}</span>
                </span>
                <a href="{{ route('operator.dashboard') }}" class="text-xs bg-purple-50 text-purple-700 hover:bg-purple-100 font-bold px-3 py-1.5 rounded-lg transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 0118 0z"/></svg>
                    <span>Ir para Controle de Ciclo</span>
                </a>
            </div>
        </header>

        <main class="p-8 max-w-7xl w-full mx-auto space-y-8">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-purple-700 via-indigo-700 to-blue-800 rounded-3xl p-8 text-white shadow-xl shadow-purple-900/10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <span class="bg-white/20 text-white font-bold text-xs uppercase tracking-wider px-3 py-1 rounded-full mb-3 inline-block">Set nº 2026-IOOT &bull; Nível Engenharia</span>
                    <h1 class="text-3xl font-extrabold tracking-tight">Manual de Montagem Ilustrado: Smart Shelf IoT</h1>
                    <p class="text-purple-100 text-sm mt-1 max-w-2xl">Siga as instruções passo a passo com precisão milimétrica. A célula de carga valida o peso automaticamente a cada etapa.</p>
                </div>
                <div class="bg-white/10 backdrop-blur-md px-6 py-4 rounded-2xl border border-white/20 text-center shrink-0">
                    <div class="text-2xl font-black">45 min</div>
                    <div class="text-xs text-purple-200">Tempo Total Estimado</div>
                </div>
            </div>

            <!-- Bill of Materials (BOM) Quick Grid -->
            <div class="soft-card p-6">
                <h3 class="font-bold text-slate-800 text-base mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Componentes do Kit (BOM - Bill of Materials)
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-blue-100 text-blue-700 font-bold flex items-center justify-center shrink-0">01</span>
                        <div>
                            <div class="font-bold text-slate-800">ESP32 NodeMCU</div>
                            <div class="text-slate-400">Wi-Fi & Bluetooth</div>
                        </div>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-amber-100 text-amber-700 font-bold flex items-center justify-center shrink-0">02</span>
                        <div>
                            <div class="font-bold text-slate-800">Célula de Carga</div>
                            <div class="text-slate-400">Strain Gauge 5kg</div>
                        </div>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-rose-100 text-rose-700 font-bold flex items-center justify-center shrink-0">03</span>
                        <div>
                            <div class="font-bold text-slate-800">Módulo HX711</div>
                            <div class="text-slate-400">Conversor ADC 24-bit</div>
                        </div>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center shrink-0">04</span>
                        <div>
                            <div class="font-bold text-slate-800">Sensor Ultrassom</div>
                            <div class="text-slate-400">HC-SR04 Trigger/Echo</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Steps Detailed Section with Technical Isometric Diagrams -->
            <div class="space-y-8">
                <!-- Step 1 Card -->
                <div class="soft-card p-8 overflow-hidden hover:shadow-lg transition">
                    <div class="flex flex-col lg:flex-row gap-8 items-center">
                        <div class="w-full lg:w-1/2 rounded-2xl overflow-hidden border border-slate-200 bg-slate-50 shadow-inner group">
                            <img src="{{ asset('images/step1.jpg') }}" alt="Passo 1 - Montagem Estrutural da Balança" class="w-full h-auto object-cover group-hover:scale-105 transition duration-500">
                        </div>
                        <div class="w-full lg:w-1/2 space-y-4">
                            <div class="flex items-center gap-3">
                                <span class="w-9 h-9 rounded-xl bg-purple-600 text-white font-extrabold flex items-center justify-center text-sm shadow-md shadow-purple-600/30">1</span>
                                <h3 class="text-2xl font-bold text-slate-900">Passo 1: Montagem Estrutural da Balança</h3>
                            </div>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                Fixe a barra metálica da célula de carga na base plástica estriada (estilo LEGO) utilizando calços amortecedores para garantir a livre deflexão do sensor.
                            </p>
                            <div class="p-4 bg-purple-50/70 border border-purple-100 rounded-xl space-y-2 text-xs text-purple-900 font-medium">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-purple-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Atenção à seta direcional indicando vetor de gravidade (para baixo).</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-purple-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Aperte os parafusos M3 sem exceder o torque recomendado.</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-purple-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Peso esperado desta sub-montagem: <strong>100g (±5g)</strong>.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2 Card -->
                <div class="soft-card p-8 overflow-hidden hover:shadow-lg transition">
                    <div class="flex flex-col lg:flex-row-reverse gap-8 items-center">
                        <div class="w-full lg:w-1/2 rounded-2xl overflow-hidden border border-slate-200 bg-slate-50 shadow-inner group">
                            <img src="{{ asset('images/step2.jpg') }}" alt="Passo 2 - Fixação de Sensores e Amplificador HX711" class="w-full h-auto object-cover group-hover:scale-105 transition duration-500">
                        </div>
                        <div class="w-full lg:w-1/2 space-y-4">
                            <div class="flex items-center gap-3">
                                <span class="w-9 h-9 rounded-xl bg-indigo-600 text-white font-extrabold flex items-center justify-center text-sm shadow-md shadow-indigo-600/30">2</span>
                                <h3 class="text-2xl font-bold text-slate-900">Passo 2: Encaixe do Módulo HX711 e Ultrassom</h3>
                            </div>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                Encaixe os módulos eletrônicos sobre a placa superior nas posições indicadas pelos pinos guia de encaixe rápido até ouvir o clique de travamento mecânico.
                            </p>
                            <div class="p-4 bg-indigo-50/70 border border-indigo-100 rounded-xl space-y-2 text-xs text-indigo-900 font-medium">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Vermelho: E+ | Preto: E- | Branco: A- | Verde: A+.</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Conecte o sensor HC-SR04 com os transdutores voltados para a frente da bancada.</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Peso esperado do produto completo: <strong>150g (±6g)</strong>.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
