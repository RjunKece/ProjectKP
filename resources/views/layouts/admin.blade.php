<!DOCTYPE html>
<html lang="id" x-data="{ ...themeManager(), ...sidebarManager() }" x-init="initTheme(); initSidebar()" x-cloak>

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel') | PT. Golden Intan Berlian ERP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin Panel - ERP System PT. Golden Intan Berlian">

    @vite('resources/css/app.css')
    <link rel="shortcut icon" href="{{ asset('images/logoPTGoldenIB.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        * { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }

        /* ===== SIDEBAR SIZE ===== */
        .sidebar-expanded { width: 272px; }
        .sidebar-collapsed { width: 72px; }
        .sidebar-transition {
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-x: hidden !important;
        }
        .main-transition {
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ===== LIGHT MODE SIDEBAR ===== */
        .sidebar-glow {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border-right: 1px solid #e2e8f0;
        }
        /* ===== DARK MODE SIDEBAR ===== */
        .dark .sidebar-glow {
            background: linear-gradient(180deg, #0f172a 0%, #1e1b4b 35%, #1e1b4b 65%, #0f172a 100%);
            border-right: 1px solid rgba(99,102,241,0.12);
        }

        /* ===== MENU ITEMS ===== */
        .menu-active {
            background: linear-gradient(135deg, rgba(212,175,55,0.12), rgba(212,175,55,0.05));
            border-left: 3px solid #d4af37;
            color: #92710a;
        }
        .dark .menu-active {
            color: #f5e6b3;
        }
        .menu-item {
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
            color: #64748b;
            white-space: nowrap;
        }
        .dark .menu-item {
            color: #94a3b8;
        }
        .menu-item:hover {
            background: rgba(0,0,0,0.03);
            border-left-color: rgba(212,175,55,0.4);
            color: #334155;
        }
        .dark .menu-item:hover {
            background: rgba(255,255,255,0.04);
            color: #f5e6b3;
        }

        /* ===== SIDEBAR TEXT COLORS (LIGHT) ===== */
        .sidebar-text-primary { color: #0f172a; }
        .sidebar-text-secondary { color: #64748b; }
        .sidebar-text-muted { color: #94a3b8; }
        .dark .sidebar-text-primary { color: #ffffff; }
        .dark .sidebar-text-secondary { color: #94a3b8; }
        .dark .sidebar-text-muted { color: #475569; }

        /* ===== SIDEBAR BORDERS ===== */
        .sidebar-border { border-color: #e2e8f0; }
        .dark .sidebar-border { border-color: rgba(255,255,255,0.05); }

        /* ===== TOOLTIP FOR COLLAPSED ===== */
        .sidebar-tooltip {
            position: absolute;
            left: calc(100% + 12px);
            top: 50%; transform: translateY(-50%);
            background: #1e293b; color: #f1f5f9;
            padding: 6px 12px; border-radius: 8px;
            font-size: 12px; font-weight: 500;
            white-space: nowrap;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.15s ease;
            z-index: 100;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        .sidebar-tooltip::before {
            content: '';
            position: absolute; right: 100%; top: 50%; transform: translateY(-50%);
            border: 5px solid transparent;
            border-right-color: #1e293b;
        }
        .menu-item:hover .sidebar-tooltip { opacity: 1; }

        /* ===== SCROLLBAR ===== */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(148,163,184,0.2); border-radius: 10px; }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: rgba(148,163,184,0.4); }

        /* ===== HEADER GLASSMORPHISM ===== */
        .header-glass {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        .dark .header-glass {
            background: rgba(12,18,34,0.85);
        }

        /* ===== COLLAPSE BUTTON ===== */
        .collapse-btn { transition: all 0.3s ease; }
        .collapse-btn:hover { background: rgba(212,175,55,0.1); color: #d4af37; }
        .collapse-btn svg { transition: transform 0.3s ease; }

        /* ===== BADGE PULSE ===== */
        .role-badge-admin {
            background: linear-gradient(135deg, rgba(212,175,55,0.15), rgba(212,175,55,0.08));
            border: 1px solid rgba(212,175,55,0.2);
            color: #92710a;
        }
        .dark .role-badge-admin {
            background: linear-gradient(135deg, rgba(212,175,55,0.2), rgba(212,175,55,0.1));
            border-color: rgba(212,175,55,0.3);
            color: #f5e6b3;
        }
    </style>
</head>

<body class="bg-[#f1f5f9] dark:bg-[#0c1222] text-slate-800 dark:text-slate-200 transition-colors duration-300">

<div class="flex h-screen overflow-hidden">

    <!-- ================= SIDEBAR ================= -->
    <aside :class="collapsed ? 'sidebar-collapsed' : 'sidebar-expanded'"
           class="sidebar-glow sidebar-transition fixed inset-y-0 left-0 z-40
                  flex flex-col
                  shadow-[2px_0_16px_rgba(0,0,0,0.06)] dark:shadow-[4px_0_24px_rgba(0,0,0,0.3)]">

        <!-- LOGO AREA -->
        <div class="flex items-center h-[72px] px-4 border-b sidebar-border shrink-0">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#f5e6b3] to-[#d4af37]
                            flex items-center justify-center shrink-0
                            shadow-lg shadow-amber-500/20">
                    <img src="{{ asset('images/logoPTGoldenIB.ico') }}" class="w-6 h-6">
                </div>
                <div x-show="!collapsed" x-transition.opacity.duration.200ms class="min-w-0 overflow-hidden">
                    <p class="text-sm font-bold sidebar-text-primary truncate leading-tight">
                        PT. Golden Intan Berlian
                    </p>
                    <p class="text-[10px] text-[#d4af37] tracking-[0.2em] font-semibold uppercase">
                        Admin Panel
                    </p>
                </div>
            </div>
        </div>

        <!-- ADMIN PROFILE MINI -->
        <div class="px-3 py-4 border-b sidebar-border shrink-0">
            <div class="flex items-center gap-3 px-2">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#f5e6b3] to-[#d4af37]
                            flex items-center justify-center font-bold text-slate-900 text-xs shrink-0
                            ring-2 ring-[#d4af37]/30 ring-offset-2 ring-offset-white dark:ring-offset-slate-900">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div x-show="!collapsed" x-transition.opacity.duration.200ms class="min-w-0 overflow-hidden">
                    <p class="text-[13px] font-semibold sidebar-text-primary truncate">{{ auth()->user()->name }}</p>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold role-badge-admin mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#d4af37] animate-pulse"></span>
                        Super Admin
                    </span>
                </div>
            </div>
        </div>

        <!-- MENU -->
        <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto sidebar-scroll">

            <!-- Section: Overview -->
            <p x-show="!collapsed" x-transition.opacity.duration.200ms
               class="px-3 mb-3 text-[10px] uppercase tracking-[0.2em] text-slate-500 font-semibold">
                Overview
            </p>
            <div x-show="collapsed" class="flex justify-center mb-3">
                <div class="w-6 h-px bg-slate-700"></div>
            </div>

            <a href="{{ route('admin.dashboard') }}"
               class="menu-item relative flex items-center gap-3 px-3 py-2.5 rounded-r-lg text-sm
               {{ request()->routeIs('admin.dashboard') ? 'menu-active font-semibold' : 'text-slate-400' }}">
                <span class="text-lg shrink-0 w-6 text-center">📊</span>
                <span x-show="!collapsed" x-transition.opacity.duration.200ms>Dashboard</span>
                <span x-show="collapsed" class="sidebar-tooltip">Dashboard</span>
            </a>

            <!-- Section: Administration -->
            <p x-show="!collapsed" x-transition.opacity.duration.200ms
               class="px-3 mt-6 mb-3 text-[10px] uppercase tracking-[0.2em] text-slate-500 font-semibold">
                Administration
            </p>
            <div x-show="collapsed" class="flex justify-center my-3">
                <div class="w-6 h-px bg-slate-700"></div>
            </div>

            <a href="{{ route('admin.users') }}"
               class="menu-item relative flex items-center gap-3 px-3 py-2.5 rounded-r-lg text-sm
               {{ request()->routeIs('admin.users*') ? 'menu-active font-semibold' : 'text-slate-400' }}">
                <span class="text-lg shrink-0 w-6 text-center">👤</span>
                <span x-show="!collapsed" x-transition.opacity.duration.200ms>User Management</span>
                <span x-show="collapsed" class="sidebar-tooltip">User Management</span>
            </a>

            <a href="{{ route('admin.activities') }}"
               class="menu-item relative flex items-center gap-3 px-3 py-2.5 rounded-r-lg text-sm
               {{ request()->routeIs('admin.activities') ? 'menu-active font-semibold' : 'text-slate-400' }}">
                <span class="text-lg shrink-0 w-6 text-center">📝</span>
                <span x-show="!collapsed" x-transition.opacity.duration.200ms>Monitoring Aktivitas</span>
                <span x-show="collapsed" class="sidebar-tooltip">Monitoring Aktivitas</span>
            </a>

            <!-- Section: Analytics -->
            <p x-show="!collapsed" x-transition.opacity.duration.200ms
               class="px-3 mt-6 mb-3 text-[10px] uppercase tracking-[0.2em] text-slate-500 font-semibold">
                Analytics
            </p>
            <div x-show="collapsed" class="flex justify-center my-3">
                <div class="w-6 h-px bg-slate-700"></div>
            </div>

            <a href="{{ route('admin.reports') }}"
               class="menu-item relative flex items-center gap-3 px-3 py-2.5 rounded-r-lg text-sm
               {{ request()->routeIs('admin.reports*') ? 'menu-active font-semibold' : 'text-slate-400' }}">
                <span class="text-lg shrink-0 w-6 text-center">📑</span>
                <span x-show="!collapsed" x-transition.opacity.duration.200ms>Reports</span>
                <span x-show="collapsed" class="sidebar-tooltip">Reports</span>
            </a>

        </nav>

        <!-- COLLAPSE TOGGLE + FOOTER -->
        <div class="border-t sidebar-border shrink-0">
            <!-- Collapse Button -->
            <button @click="toggleSidebar()"
                    class="collapse-btn w-full flex items-center gap-3 px-5 py-3.5 text-slate-400 text-sm">
                <svg :class="collapsed && 'rotate-180'" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
                <span x-show="!collapsed" x-transition.opacity.duration.200ms class="text-xs font-medium">Collapse</span>
            </button>

            <!-- Version -->
            <div x-show="!collapsed" x-transition.opacity.duration.200ms
                 class="px-5 pb-4">
                <p class="text-[10px] sidebar-text-muted text-center">
                    ERP System v1.0 &copy; {{ date('Y') }}
                </p>
            </div>
        </div>

    </aside>


    <!-- ================= MAIN ================= -->
    <div :class="collapsed ? 'ml-[72px]' : 'ml-[272px]'"
         class="flex-1 main-transition flex flex-col min-w-0">

        <!-- ================= HEADER ================= -->
        <header class="header-glass h-[72px] flex items-center justify-between px-6 lg:px-8
                       border-b border-slate-200/80 dark:border-slate-700/50
                       sticky top-0 z-30">

            <div class="min-w-0">
                <h1 class="text-lg font-bold text-slate-900 dark:text-white truncate">
                    @yield('title', 'Dashboard')
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Welcome back, <span class="font-medium text-slate-700 dark:text-slate-300">{{ auth()->user()->name }}</span>
                </p>
            </div>

            <div class="flex items-center gap-2">

                <!-- Warning flash -->
                @if(session('warning'))
                <div class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-lg text-xs text-amber-700 dark:text-amber-300">
                    ⚠️ {{ session('warning') }}
                </div>
                @endif

                <!-- DARK / LIGHT -->
                <button @click="toggleTheme()"
                    class="flex items-center justify-center w-10 h-10 rounded-xl
                        border border-slate-200 dark:border-slate-600
                        hover:bg-slate-100 dark:hover:bg-slate-800
                        hover:border-[#d4af37]/50
                        transition-all duration-200"
                    title="Toggle Theme">
                    <span x-text="theme === 'light' ? '🌙' : '☀️'" class="text-base"></span>
                </button>

                <!-- PROFILE DROPDOWN -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                            @click.outside="open = false"
                            class="flex items-center gap-2 cursor-pointer
                                   hover:bg-slate-100 dark:hover:bg-slate-800
                                   rounded-xl px-2 py-1.5 transition-all duration-200
                                   border border-transparent hover:border-slate-200 dark:hover:border-slate-700">

                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#f5e6b3] to-[#d4af37]
                                    flex items-center justify-center
                                    font-bold text-slate-900 text-xs shadow-md">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>

                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-60
                                bg-white dark:bg-slate-800
                                border border-slate-200 dark:border-slate-700
                                rounded-2xl shadow-2xl overflow-hidden z-50">

                        <div class="px-4 py-3 bg-gradient-to-r from-slate-50 to-white dark:from-slate-800 dark:to-slate-800
                                    border-b border-slate-200 dark:border-slate-700">
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ auth()->user()->email }}</p>
                            <span class="inline-flex items-center gap-1 mt-1.5 px-2 py-0.5 rounded-full text-[10px] font-semibold
                                         bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400
                                         border border-amber-200 dark:border-amber-700/50">
                                🛡️ Super Admin
                            </span>
                        </div>

                        <a href="{{ route('profile.index') }}"
                           class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 dark:text-slate-300
                                  hover:bg-[#f5e6b3]/30 dark:hover:bg-slate-700 transition-colors">
                            <span class="text-base">👤</span> My Profile
                        </a>

                        <div class="border-t border-slate-100 dark:border-slate-700">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-600 dark:text-red-400
                                              hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <span class="text-base">🚪</span> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </header>


        <!-- ================= CONTENT ================= -->
        <main class="flex-1 overflow-y-auto px-6 lg:px-8 py-6
                     bg-[#f1f5f9] dark:bg-[#0c1222] transition-colors">

            @yield('content')

        </main>

    </div>

</div>

<!-- ================= SCRIPTS ================= -->
<script>
function themeManager() {
    return {
        theme: 'light',
        initTheme() {
            const saved = localStorage.getItem('erp_theme');
            if (saved) this.theme = saved;
            this.applyTheme();
        },
        toggleTheme() {
            this.theme = this.theme === 'light' ? 'dark' : 'light';
            localStorage.setItem('erp_theme', this.theme);
            this.applyTheme();
        },
        applyTheme() {
            if (this.theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    }
}

function sidebarManager() {
    return {
        collapsed: false,
        initSidebar() {
            const saved = localStorage.getItem('erp_sidebar_collapsed');
            this.collapsed = saved === 'true';
        },
        toggleSidebar() {
            this.collapsed = !this.collapsed;
            localStorage.setItem('erp_sidebar_collapsed', this.collapsed);
        }
    }
}
</script>

@stack('scripts')

</body>
</html>
