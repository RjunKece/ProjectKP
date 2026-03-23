<!DOCTYPE html>
<html lang="id" x-data="themeManager()" x-init="initTheme()" :class="theme">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel') | PT. Golden Intan Berlian ERP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite('resources/css/app.css')
    <link rel="shortcut icon" href="{{ asset('images/logoPTGoldenIB.ico') }}">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

</head>

<body class="min-h-screen flex bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-colors duration-300">

<!-- ================= SIDEBAR ================= -->
<aside class="w-72 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-700 flex flex-col transition-colors">

    <!-- LOGO -->
    <div class="flex items-center gap-3 px-6 py-6 border-b border-slate-200 dark:border-slate-700">
        <img src="{{ asset('images/logoPTGoldenIB.ico') }}"
             class="w-10 h-10 rounded-lg bg-white p-1 border border-slate-200">
        <div>
            <p class="text-sm font-semibold text-slate-900 dark:text-white">
                PT. Golden Intan Berlian
            </p>
            <p class="text-xs text-[#d4af37] tracking-wide font-medium">
                ERP SYSTEM
            </p>
        </div>
    </div>

    <!-- MENU -->
    <nav class="flex-1 px-4 py-6 space-y-1 text-sm">

        <p class="px-3 mb-2 text-xs uppercase tracking-widest text-slate-400">
            Overview
        </p>

        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-4 py-2 rounded-lg transition
           {{ request()->routeIs('admin.dashboard')
                ? 'bg-[#f5e6b3] text-[#8a6d1d] font-semibold'
                : 'hover:bg-slate-100 dark:hover:bg-slate-800' }}">
            📊 Dashboard
        </a>

        <p class="px-3 mt-6 mb-2 text-xs uppercase tracking-widest text-slate-400">
            Administration
        </p>

        <a href="{{ route('admin.users') }}"
           class="flex items-center gap-3 px-4 py-2 rounded-lg transition
           {{ request()->routeIs('admin.users*')
                ? 'bg-[#f5e6b3] text-[#8a6d1d] font-semibold'
                : 'hover:bg-slate-100 dark:hover:bg-slate-800' }}">
            👤 User Management
        </a>

        <a href="{{ route('admin.activities') }}"
           class="flex items-center gap-3 px-4 py-2 rounded-lg transition
           {{ request()->routeIs('admin.activities')
                ? 'bg-[#f5e6b3] text-[#8a6d1d] font-semibold'
                : 'hover:bg-slate-100 dark:hover:bg-slate-800' }}">
            📝 Monitoring Aktivitas
        </a>

        <p class="px-3 mt-6 mb-2 text-xs uppercase tracking-widest text-slate-400">
            Analytics
        </p>

        <a href="{{ route('admin.reports') }}"
           class="flex items-center gap-3 px-4 py-2 rounded-lg transition
           {{ request()->routeIs('admin.reports')
                ? 'bg-[#f5e6b3] text-[#8a6d1d] font-semibold'
                : 'hover:bg-slate-100 dark:hover:bg-slate-800' }}">
            📑 Reports
        </a>

    </nav>

</aside>


<!-- ================= MAIN ================= -->
<div class="flex-1 flex flex-col">

    <!-- ================= TOPBAR ================= -->
    <header class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between px-8 transition-colors">

        <div>
            <h1 class="text-lg font-semibold text-slate-900 dark:text-white">
                @yield('title', 'Dashboard')
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">
                Welcome back, {{ auth()->user()->name }}
            </p>
        </div>

        <div class="flex items-center gap-4">

            <!-- THEME TOGGLE -->
            <button
                @click="toggleTheme()"
                class="px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-600
                hover:bg-slate-100 dark:hover:bg-slate-800 transition">

                <span x-show="theme === 'light'">🌙 Dark</span>
                <span x-show="theme === 'dark'">☀️ Light</span>

            </button>


            <!-- PROFILE -->
            <details class="relative">
                <summary class="flex items-center gap-2 cursor-pointer list-none">

                    <div
                        class="w-9 h-9 rounded-full bg-[#f5e6b3] flex items-center justify-center font-semibold text-[#8a6d1d]">

                        {{ strtoupper(substr(auth()->user()->name,0,1)) }}

                    </div>

                </summary>

                <div class="absolute right-0 mt-3 w-44 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg shadow-lg">

                    <a href="{{ route('profile.index') }}"
                       class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-700">
                        Profile
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full text-left px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-700">
                            Logout
                        </button>
                    </form>

                </div>

            </details>

        </div>

    </header>


    <!-- ================= CONTENT ================= -->
    <main class="flex-1 px-8 py-6 bg-[#fafafa] dark:bg-slate-950 transition-colors">

        @yield('content')

    </main>

</div>



<!-- ================= GLOBAL COMPONENT STYLE ================= -->
<style>

.erp-card{
    background:#ffffff;
    border:1px solid #e5e7eb;
    border-radius:1rem;
    box-shadow:0 8px 20px rgba(0,0,0,.06);
    transition:all .25s ease;
}

.dark .erp-card{
    background:#0f172a;
    border-color:#334155;
}

.erp-card:hover{
    transform:translateY(-2px);
    border-color:#d4af37;
    box-shadow:0 14px 30px rgba(212,175,55,.18);
}

.input{
    background:#ffffff;
    border:1px solid #e5e7eb;
    border-radius:.75rem;
    padding:.65rem .9rem;
    font-size:.875rem;
}

.dark .input{
    background:#020617;
    border-color:#334155;
    color:#e2e8f0;
}

.input:focus{
    outline:none;
    border-color:#d4af37;
    box-shadow:0 0 0 2px rgba(212,175,55,.25);
}

.label{
    font-size:.75rem;
    color:#6b7280;
}

</style>


<!-- ================= THEME MANAGER ================= -->
<script>

function themeManager(){

    return{

        theme:'light',

        initTheme(){

            const saved = localStorage.getItem('erp_theme')

            if(saved){
                this.theme = saved
            }

            this.applyTheme()
        },

        toggleTheme(){

            this.theme = this.theme === 'light' ? 'dark' : 'light'

            localStorage.setItem('erp_theme',this.theme)

            this.applyTheme()
        },

        applyTheme(){

            if(this.theme === 'dark'){

                document.documentElement.classList.add('dark')

            }else{

                document.documentElement.classList.remove('dark')

            }

        }

    }

}

</script>

</body>
</html>