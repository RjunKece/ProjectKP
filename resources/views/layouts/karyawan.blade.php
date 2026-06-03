<!DOCTYPE html>
<html lang="id" x-data="{ ...themeManager(), ...sidebarManager() }" x-init="initTheme(); initSidebar()" x-cloak>

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Employee Portal') | PT. Golden Intan Berlian ERP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Employee Portal - ERP System PT. Golden Intan Berlian">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="shortcut icon" href="{{ asset('images/logoPTGoldenIB.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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

        /* ===== LIGHT SIDEBAR ===== */
        .sidebar-karyawan {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border-right: 1px solid #e2e8f0;
        }
        .dark .sidebar-karyawan {
            background: linear-gradient(180deg, #0f172a 0%, #0c1222 40%, #111827 100%);
            border-right: 1px solid rgba(59,130,246,0.08);
        }

        .sidebar-border-k { border-color: #e2e8f0; }
        .dark .sidebar-border-k { border-color: rgba(255,255,255,0.05); }
        .sidebar-text-k { color: #0f172a; }
        .dark .sidebar-text-k { color: #ffffff; }

        /* ===== MENU ITEM ACTIVE ===== */
        .menu-active-k {
            background: linear-gradient(135deg, rgba(59,130,246,0.1), rgba(59,130,246,0.04));
            border-left: 3px solid #3b82f6;
            color: #1d4ed8;
        }
        .dark .menu-active-k { color: #93c5fd; }
        .menu-item-k {
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
            color: #64748b;
            white-space: nowrap;
        }
        .dark .menu-item-k { color: #94a3b8; }
        .menu-item-k:hover {
            background: rgba(59,130,246,0.04);
            border-left-color: rgba(59,130,246,0.4);
            color: #1e40af;
        }
        .dark .menu-item-k:hover {
            background: rgba(255,255,255,0.04);
            color: #93c5fd;
        }

        /* ===== TOOLTIP ===== */
        .sidebar-tooltip-k {
            position: absolute;
            left: calc(100% + 12px); top: 50%; transform: translateY(-50%);
            background: #1e293b; color: #f1f5f9;
            padding: 6px 12px; border-radius: 8px;
            font-size: 12px; font-weight: 500;
            white-space: nowrap; pointer-events: none;
            opacity: 0; transition: opacity 0.15s ease;
            z-index: 100; box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        .sidebar-tooltip-k::before {
            content: '';
            position: absolute; right: 100%; top: 50%; transform: translateY(-50%);
            border: 5px solid transparent; border-right-color: #1e293b;
        }
        .menu-item-k:hover .sidebar-tooltip-k { opacity: 1; }

        /* ===== SCROLLBAR ===== */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(148,163,184,0.2); border-radius: 10px; }

        /* ===== HEADER GLASS ===== */
        .header-glass {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
        }
        .dark .header-glass { background: rgba(12,18,34,0.85); }

        /* ===== COLLAPSE BUTTON ===== */
        .collapse-btn-k { transition: all 0.3s ease; }
        .collapse-btn-k:hover { background: rgba(59,130,246,0.08); color: #60a5fa; }

        /* ===== EMPLOYEE BADGE ===== */
        .role-badge-employee {
            background: linear-gradient(135deg, rgba(59,130,246,0.1), rgba(59,130,246,0.05));
            border: 1px solid rgba(59,130,246,0.15);
            color: #1d4ed8;
        }
        .dark .role-badge-employee {
            background: linear-gradient(135deg, rgba(59,130,246,0.2), rgba(59,130,246,0.1));
            border-color: rgba(59,130,246,0.3);
            color: #93c5fd;
        }

        /* ===== ONLINE DOT ===== */
        .online-dot {
            width: 8px; height: 8px; background: #22c55e;
            border-radius: 50%; border: 2px solid #ffffff;
            position: absolute; bottom: 0; right: 0;
        }
        .dark .online-dot { border-color: #0f172a; }
    </style>
</head>

<body class="bg-[#f1f5f9] dark:bg-[#0c1222] text-slate-800 dark:text-slate-200 transition-colors duration-300">

<div class="flex h-screen overflow-hidden">

    <!-- ================= SIDEBAR (KARYAWAN) ================= -->
    <aside :class="collapsed ? 'sidebar-collapsed' : 'sidebar-expanded'"
           class="sidebar-karyawan sidebar-transition fixed inset-y-0 left-0 z-40
                  flex flex-col
                  shadow-[2px_0_16px_rgba(0,0,0,0.06)] dark:shadow-[4px_0_24px_rgba(0,0,0,0.25)]">

        <!-- LOGO AREA -->
        <div class="flex items-center h-[72px] px-4 border-b sidebar-border-k shrink-0">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#f5e6b3] to-[#d4af37]
                            flex items-center justify-center shrink-0
                            shadow-lg shadow-amber-500/20">
                    <img src="{{ asset('images/logoPTGoldenIB.ico') }}" class="w-6 h-6">
                </div>
                <div x-show="!collapsed" x-transition.opacity.duration.200ms class="min-w-0 overflow-hidden">
                    <p class="text-sm font-bold sidebar-text-k truncate leading-tight">
                        PT. Golden Intan Berlian
                    </p>
                    <p class="text-[10px] text-blue-400 tracking-[0.15em] font-semibold uppercase">
                        Employee Portal
                    </p>
                </div>
            </div>
        </div>

        <!-- USER PROFILE CARD -->
        <div class="px-3 py-4 border-b sidebar-border-k shrink-0">
            <div class="flex items-center gap-3 px-2">
                <div class="relative shrink-0">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-400 to-blue-600
                                flex items-center justify-center font-bold text-white text-xs
                                ring-2 ring-blue-500/30 ring-offset-2 ring-offset-white dark:ring-offset-slate-900">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span class="online-dot"></span>
                </div>
                <div x-show="!collapsed" x-transition.opacity.duration.200ms class="min-w-0 overflow-hidden">
                    <p class="text-[13px] font-semibold sidebar-text-k truncate">{{ auth()->user()->name }}</p>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold role-badge-employee mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                        {{ optional(auth()->user()->division)->nama_divisi ?? 'Karyawan' }}
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

            <a href="{{ route('karyawan.dashboard') }}"
               class="menu-item-k relative flex items-center gap-3 px-3 py-2.5 rounded-r-lg text-sm
               {{ request()->routeIs('karyawan.dashboard') ? 'menu-active-k font-semibold' : 'text-slate-400' }}">
                <span class="text-lg shrink-0 w-6 text-center">📊</span>
                <span x-show="!collapsed" x-transition.opacity.duration.200ms>My Dashboard</span>
                <span x-show="collapsed" class="sidebar-tooltip-k">My Dashboard</span>
            </a>

            <!-- Section: Data Saya -->
            <p x-show="!collapsed" x-transition.opacity.duration.200ms
               class="px-3 mt-6 mb-3 text-[10px] uppercase tracking-[0.2em] text-slate-500 font-semibold">
                Data Saya
            </p>
            <div x-show="collapsed" class="flex justify-center my-3">
                <div class="w-6 h-px bg-slate-700"></div>
            </div>

            <a href="{{ route('karyawan.activities') }}"
               class="menu-item-k relative flex items-center gap-3 px-3 py-2.5 rounded-r-lg text-sm
               {{ request()->routeIs('karyawan.activities') ? 'menu-active-k font-semibold' : 'text-slate-400' }}">
                <span class="text-lg shrink-0 w-6 text-center">📝</span>
                <span x-show="!collapsed" x-transition.opacity.duration.200ms>Aktivitas Saya</span>
                <span x-show="collapsed" class="sidebar-tooltip-k">Aktivitas Saya</span>
            </a>

            <a href="{{ route('karyawan.reports') }}"
               class="menu-item-k relative flex items-center gap-3 px-3 py-2.5 rounded-r-lg text-sm
               {{ request()->routeIs('karyawan.reports*') ? 'menu-active-k font-semibold' : 'text-slate-400' }}">
                <span class="text-lg shrink-0 w-6 text-center">📑</span>
                <span x-show="!collapsed" x-transition.opacity.duration.200ms>Pusat Laporan</span>
                <span x-show="collapsed" class="sidebar-tooltip-k">Pusat Laporan</span>
            </a>

        </nav>

        <!-- COLLAPSE TOGGLE + FOOTER -->
        <div class="border-t border-white/5 shrink-0">
            <button @click="toggleSidebar()"
                    class="collapse-btn-k w-full flex items-center gap-3 px-5 py-3.5 text-slate-400 text-sm">
                <svg :class="collapsed && 'rotate-180'" class="w-5 h-5 shrink-0 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
                <span x-show="!collapsed" x-transition.opacity.duration.200ms class="text-xs font-medium">Collapse</span>
            </button>

            <div x-show="!collapsed" x-transition.opacity.duration.200ms
                 class="px-5 pb-4">
                <p class="text-[10px] text-slate-600 text-center">
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
                        hover:border-blue-400/50
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

                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600
                                    flex items-center justify-center
                                    font-bold text-white text-xs shadow-md">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>

                        <div class="text-left hidden md:block">
                            <p class="text-sm font-medium text-slate-900 dark:text-white leading-tight">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                Karyawan
                            </p>
                        </div>

                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
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

                        <!-- User Info -->
                        <div class="px-4 py-3 bg-gradient-to-r from-slate-50 to-white dark:from-slate-800 dark:to-slate-800
                                    border-b border-slate-200 dark:border-slate-700">
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ auth()->user()->email }}</p>
                            <span class="inline-flex items-center gap-1 mt-1.5 px-2 py-0.5 rounded-full text-[10px] font-semibold
                                         bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400
                                         border border-blue-200 dark:border-blue-700/50">
                                👤 {{ optional(auth()->user()->division)->nama_divisi ?? 'Karyawan' }}
                            </span>
                        </div>

                        <a href="{{ route('profile.index') }}"
                           class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 dark:text-slate-300
                                  hover:bg-blue-50/50 dark:hover:bg-slate-700 transition-colors">
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
