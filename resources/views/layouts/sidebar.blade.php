@php
    $user = Auth::user();
    $role = $user?->role ?? 'operator';
    $currentRoute = Route::currentRouteName();
@endphp

<!-- Sidebar Backdrop on mobile -->
<div id="sidebar-backdrop"
    class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 hidden md:hidden transition-opacity">
</div>

<!-- Retractable Sidebar -->
<aside id="app-sidebar"
    class="sticky top-0 z-10 flex h-screen max-h-screen flex-shrink-0 flex-col
           overflow-hidden bg-white border-r border-slate-200/80
           transition-all duration-300 shadow-sm w-[270px]">

    <!-- Brand / Toggle Header -->
    <div class="h-20 flex-shrink-0 flex items-center justify-between px-5 border-b border-slate-100">
        <a href="#" class="flex items-center gap-3 overflow-hidden">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-purple-600 to-indigo-600 flex items-center justify-center shrink-0 shadow-md shadow-purple-500/20 overflow-hidden">
                <img
                    src="{{ asset('images/renault_logo.png') }}"
                    alt="Renault logo"
                    class="w-full h-full object-contain scale-125"
                >
            </div>

            <div class="sidebar-text flex flex-col leading-tight whitespace-nowrap">
                <span class="font-extrabold text-slate-900 tracking-tight text-base">
                    Renault <span class="text-purple-600">IIoT</span>
                </span>
                <span class="text-[11px] text-slate-400 font-medium">Smart Assembly</span>
            </div>
        </a>

        <!-- Collapse Toggle Button -->
        <button id="sidebar-toggle-btn"
            type="button"
            class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition hidden md:flex items-center justify-center"
            title="Recolher menu lateral"
            aria-label="Recolher ou expandir menu lateral">
            <svg id="toggle-icon" class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
        </button>
    </div>

    <!-- Navigation Menu Items: scrolls independently -->
    <div id="sidebar-navigation"
        class="flex-1 min-h-0 py-6 px-3.5 space-y-6 overflow-y-auto overscroll-contain">

        @if($role === 'supervisor')
            <!-- Supervisor Section -->
            <div>
                <span class="sidebar-text text-[11px] font-bold uppercase tracking-wider text-slate-400 px-3 block mb-2">
                    Painel de Controle
                </span>

                <nav class="space-y-1">
                    <a href="{{ route('supervisor.dashboard') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ $currentRoute === 'supervisor.dashboard' ? 'bg-purple-50 text-purple-700 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        <svg class="w-5 h-5 shrink-0 {{ $currentRoute === 'supervisor.dashboard' ? 'text-purple-600' : 'text-slate-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        <span class="sidebar-text whitespace-nowrap">Torre de Controle</span>
                    </a>

                    <a href="{{ route('supervisor.analytics') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ $currentRoute === 'supervisor.analytics' ? 'bg-purple-50 text-purple-700 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        <svg class="w-5 h-5 shrink-0 {{ $currentRoute === 'supervisor.analytics' ? 'text-purple-600' : 'text-slate-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span class="sidebar-text whitespace-nowrap">Análise por Bancada</span>
                    </a>
                </nav>
            </div>
        @else
            <!-- Operator Section -->
            <div>
                <span class="sidebar-text text-[11px] font-bold uppercase tracking-wider text-slate-400 px-3 block mb-2">
                    Posto de Operação
                </span>

                <nav class="space-y-1">
                    <a href="{{ route('operator.dashboard') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ $currentRoute === 'operator.dashboard' ? 'bg-purple-50 text-purple-700 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        <svg class="w-5 h-5 shrink-0 {{ $currentRoute === 'operator.dashboard' ? 'text-purple-600' : 'text-slate-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                        </svg>
                        <span class="sidebar-text whitespace-nowrap">Ciclo de Montagem</span>
                    </a>

                    <a href="{{ route('operator.instructions') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ $currentRoute === 'operator.instructions' ? 'bg-purple-50 text-purple-700 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        <svg class="w-5 h-5 shrink-0 {{ $currentRoute === 'operator.instructions' ? 'text-purple-600' : 'text-slate-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <span class="sidebar-text whitespace-nowrap">Manual Ilustrado (LEGO)</span>
                    </a>
                </nav>
            </div>
        @endif
    </div>

    <!-- User Profile & Logout Footer: always visible -->
    <div class="flex-shrink-0 p-3.5 border-t border-slate-100 bg-slate-50/50">
        <div class="flex items-center justify-between p-2 rounded-xl bg-white border border-slate-200/60 shadow-sm">
            <div class="flex items-center gap-2.5 overflow-hidden min-w-0">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-purple-500 to-indigo-500 text-white font-bold text-xs flex items-center justify-center shrink-0">
                    {{ strtoupper(substr($user->name ?? 'OP', 0, 2)) }}
                </div>

                <div class="sidebar-text min-w-0 truncate">
                    <div class="text-xs font-bold text-slate-800 truncate">
                        {{ $user->name ?? 'Operador' }}
                    </div>
                    <div class="text-[11px] text-slate-400 capitalize">
                        {{ $user->role ?? 'Operador' }}
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="m-0 p-0 flex-shrink-0">
                @csrf
                <button type="submit"
                    class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition"
                    title="Sair da Conta"
                    aria-label="Sair da conta">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>

<script>
    (function () {
        const sidebar = document.getElementById('app-sidebar');
        const toggleBtn = document.getElementById('sidebar-toggle-btn');
        const toggleIcon = document.getElementById('toggle-icon');

        if (!sidebar) return;

        const texts = sidebar.querySelectorAll('.sidebar-text');
        const storageKey = 'sidebar_collapsed';

        let isCollapsed = localStorage.getItem(storageKey) === 'true';

        function applySidebarState() {
            if (isCollapsed) {
                sidebar.style.width = '80px';

                texts.forEach(function (element) {
                    element.classList.add('hidden');
                });

                if (toggleIcon) {
                    toggleIcon.style.transform = 'rotate(180deg)';
                }

                if (toggleBtn) {
                    toggleBtn.title = 'Expandir menu lateral';
                }
            } else {
                sidebar.style.width = '270px';

                texts.forEach(function (element) {
                    element.classList.remove('hidden');
                });

                if (toggleIcon) {
                    toggleIcon.style.transform = 'rotate(0deg)';
                }

                if (toggleBtn) {
                    toggleBtn.title = 'Recolher menu lateral';
                }
            }
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                isCollapsed = !isCollapsed;

                localStorage.setItem(storageKey, String(isCollapsed));

                applySidebarState();
            });
        }

        applySidebarState();
    })();
</script>