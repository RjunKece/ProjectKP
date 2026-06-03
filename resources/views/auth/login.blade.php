@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="login-page" id="loginPage">

    {{-- THEME TOGGLE --}}
    <button class="theme-toggle" id="themeToggle" onclick="toggleLoginTheme()" title="Ganti Tema">
        <span id="themeIcon">🌙</span>
    </button>

    <div class="login-card">

        <!-- ================= LEFT : LOGIN FORM ================= -->
        <div class="login-left">

            <div class="brand">
                <div class="brand-icon">
                    <img src="{{ asset('images/logoPTGoldenIB.ico') }}" alt="PT Golden IB">
                </div>
                <div class="brand-text">
                    <span class="brand-name">ERP System</span>
                    <span class="brand-sub">PT. Golden Intan Berlian</span>
                </div>
            </div>

            <h1 class="title">Welcome back</h1>
            <p class="subtitle">
                Masuk ke sistem untuk mengelola operasional perusahaan
            </p>

            {{-- ERROR MESSAGES --}}
            @if($errors->any())
            <div class="error-box" id="errorBox">
                <div class="error-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                </div>
                <div class="error-content">
                    <p class="error-title">Login Gagal</p>
                    @foreach($errors->all() as $error)
                        <p class="error-msg">{{ $error }}</p>
                    @endforeach
                </div>
                <button class="error-close" onclick="document.getElementById('errorBox').style.display='none'">✕</button>
            </div>
            @endif

            {{-- SESSION MESSAGE (after logout etc) --}}
            @if(session('message'))
            <div class="info-box" id="infoBox">
                <div class="info-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
                    </svg>
                </div>
                <div class="info-content">
                    <p class="info-msg">{{ session('message') }}</p>
                </div>
                <button class="info-close" onclick="document.getElementById('infoBox').style.display='none'">✕</button>
            </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <div class="field">
                    <label>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="4" width="20" height="16" rx="3"/><path d="M22 7l-10 7L2 7"/>
                        </svg>
                        Email Address
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="admin@goldenib.co.id"
                        required
                        autofocus
                        class="{{ $errors->has('email') ? 'input-error' : '' }}">
                </div>

                <div class="field">
                    <label>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                        </svg>
                        Password
                    </label>
                    <div class="password-wrap">
                        <input
                            type="password"
                            name="password"
                            id="passwordInput"
                            placeholder="Masukkan password"
                            required>
                        <button type="button" class="toggle-pass" onclick="togglePassword()">
                            <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
                    </svg>
                    Sign In
                </button>
            </form>

            <p class="auth-switch">
                Belum punya akun?
                <a href="{{ route('register') }}">Daftar di sini</a>
            </p>

            <footer>
                <div class="footer-line"></div>
                <p>&copy; {{ date('Y') }} PT Golden Intan Berlian. All rights reserved.</p>
            </footer>

        </div>

        <!-- ================= RIGHT : COMPANY PANEL ================= -->
        <div class="login-right">

            <div class="right-content">
                <div class="company-badge">Enterprise Resource Planning</div>

                <div class="company-title">
                    <span>PT</span>
                    <span>Golden</span>
                    <span class="highlight">Intan Berlian</span>
                </div>

                <p class="company-desc">
                    Sistem ERP internal terintegrasi untuk mendukung operasional,
                    monitoring kinerja, dan pengambilan keputusan strategis perusahaan.
                </p>

                <div class="feature-cards">
                    <div class="feature-card">
                        <div class="fc-icon">📊</div>
                        <strong>Dashboard</strong>
                        <span>Analytics</span>
                    </div>
                    <div class="feature-card">
                        <div class="fc-icon">👥</div>
                        <strong>HR</strong>
                        <span>Management</span>
                    </div>
                    <div class="feature-card">
                        <div class="fc-icon">📑</div>
                        <strong>Reports</strong>
                        <span>System</span>
                    </div>
                </div>

                <div class="trust-indicators">
                    <div class="trust-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        Secured SSL
                    </div>
                    <div class="trust-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Role-based Access
                    </div>
                </div>
            </div>

            <div class="right-bg-circles">
                <div class="circle c1"></div>
                <div class="circle c2"></div>
                <div class="circle c3"></div>
            </div>
        </div>

    </div>

</div>

<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    input.type = input.type === 'password' ? 'text' : 'password';
}

function toggleLoginTheme() {
    const page = document.getElementById('loginPage');
    const icon = document.getElementById('themeIcon');
    const isLight = page.classList.toggle('light-mode');
    icon.textContent = isLight ? '🌙' : '☀️';
    localStorage.setItem('erp_login_theme', isLight ? 'light' : 'dark');
}

// Restore saved theme
(function() {
    const saved = localStorage.getItem('erp_login_theme');
    if (saved === 'light') {
        document.getElementById('loginPage').classList.add('light-mode');
        document.getElementById('themeIcon').textContent = '🌙';
    } else {
        document.getElementById('themeIcon').textContent = '☀️';
    }
})();
</script>

@include('auth.partials.login-styles')
@endsection
