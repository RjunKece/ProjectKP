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

<style>
/* ============= RESET ============= */
.login-page *, .login-page *::before, .login-page *::after {
    box-sizing: border-box;
    font-family: 'Inter', system-ui, sans-serif;
    margin: 0; padding: 0;
}

/* ============= CSS VARS: DARK (DEFAULT) ============= */
.login-page {
    --page-bg: #0c1222;
    --card-bg: #111a2e;
    --card-border: rgba(255,255,255,0.04);
    --card-shadow: rgba(0,0,0,0.4);
    --input-bg: #0c1222;
    --input-border: #1e2d4a;
    --input-focus-bg: #0f1729;
    --text-primary: #f1f5f9;
    --text-secondary: #94a3b8;
    --text-muted: #64748b;
    --text-dim: #475569;
    --right-bg: #162036;
    --right-bg2: #0f172a;
    --right-border: rgba(212,175,55,0.08);
    --error-bg: rgba(239,68,68,0.08);
    --error-border: rgba(239,68,68,0.2);
    --error-text: #f87171;
    --error-title: #fca5a5;
    --info-bg: rgba(59,130,246,0.08);
    --info-border: rgba(59,130,246,0.2);
    --info-text: #60a5fa;
    --feature-bg: rgba(255,255,255,0.04);
    --feature-border: rgba(255,255,255,0.06);
    --feature-hover-bg: rgba(212,175,55,0.08);
    --feature-hover-border: rgba(212,175,55,0.15);
    --footer-line: #1e2d4a;
    --toggle-bg: rgba(255,255,255,0.06);
    --toggle-border: rgba(255,255,255,0.08);
}

/* ============= CSS VARS: LIGHT ============= */
.login-page.light-mode {
    --page-bg: #f0f2f5;
    --card-bg: #ffffff;
    --card-border: rgba(0,0,0,0.06);
    --card-shadow: rgba(0,0,0,0.12);
    --input-bg: #f8fafc;
    --input-border: #e2e8f0;
    --input-focus-bg: #ffffff;
    --text-primary: #0f172a;
    --text-secondary: #475569;
    --text-muted: #94a3b8;
    --text-dim: #cbd5e1;
    --right-bg: #0f172a;
    --right-bg2: #0c1222;
    --right-border: rgba(212,175,55,0.1);
    --error-bg: rgba(239,68,68,0.06);
    --error-border: rgba(239,68,68,0.15);
    --error-text: #dc2626;
    --error-title: #b91c1c;
    --info-bg: rgba(59,130,246,0.06);
    --info-border: rgba(59,130,246,0.15);
    --info-text: #2563eb;
    --feature-bg: rgba(255,255,255,0.04);
    --feature-border: rgba(255,255,255,0.06);
    --feature-hover-bg: rgba(212,175,55,0.08);
    --feature-hover-border: rgba(212,175,55,0.15);
    --footer-line: #e2e8f0;
    --toggle-bg: rgba(0,0,0,0.04);
    --toggle-border: rgba(0,0,0,0.08);
}

/* ============= PAGE ============= */
.login-page {
    width: 100vw; height: 100vh;
    display: flex; align-items: center; justify-content: center;
    background: var(--page-bg);
    position: relative; overflow: hidden;
    transition: background 0.4s ease;
}
.login-page::before {
    content: ''; position: absolute;
    top: -40%; left: -20%;
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(212,175,55,0.06) 0%, transparent 70%);
    border-radius: 50%; pointer-events: none;
}

/* ============= THEME TOGGLE ============= */
.theme-toggle {
    position: absolute; top: 24px; right: 24px; z-index: 50;
    width: 42px; height: 42px; border-radius: 12px;
    background: var(--toggle-bg);
    border: 1px solid var(--toggle-border);
    cursor: pointer; display: flex;
    align-items: center; justify-content: center;
    font-size: 18px;
    transition: all 0.3s ease;
}
.theme-toggle:hover {
    background: rgba(212,175,55,0.12);
    border-color: rgba(212,175,55,0.3);
    transform: scale(1.05);
}

/* ============= CARD ============= */
.login-card {
    width: 100%; max-width: 960px;
    background: var(--card-bg);
    border-radius: 20px;
    display: grid; grid-template-columns: 1fr 1fr;
    overflow: hidden;
    box-shadow: 0 24px 64px var(--card-shadow), 0 0 0 1px var(--card-border);
    animation: fadeUp .5s ease;
    position: relative; z-index: 1;
    transition: background 0.4s ease, box-shadow 0.4s ease;
}
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ============= LEFT ============= */
.login-left {
    padding: 44px 44px 36px;
    display: flex; flex-direction: column; justify-content: center;
}

.brand { display: flex; align-items: center; gap: 10px; margin-bottom: 28px; }
.brand-icon {
    width: 40px; height: 40px;
    background: linear-gradient(135deg, #f5e6b3, #d4af37);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 14px rgba(212,175,55,0.25);
}
.brand-icon img { width: 20px; }
.brand-text { display: flex; flex-direction: column; }
.brand-name { font-weight: 700; font-size: 14px; color: var(--text-primary); transition: color 0.3s; }
.brand-sub  { font-size: 10px; color: var(--text-muted); margin-top: 1px; transition: color 0.3s; }

.title {
    font-size: 26px; font-weight: 800; color: var(--text-primary);
    margin-bottom: 4px; letter-spacing: -0.02em; transition: color 0.3s;
}
.subtitle {
    font-size: 12px; color: var(--text-muted); margin-bottom: 24px; line-height: 1.5; transition: color 0.3s;
}

/* ===== ERROR BOX ===== */
.error-box {
    display: flex; align-items: flex-start; gap: 10px;
    background: var(--error-bg); border: 1px solid var(--error-border);
    border-radius: 10px; padding: 11px 13px; margin-bottom: 18px;
    animation: shakeIn 0.4s ease; transition: all 0.3s;
}
@keyframes shakeIn {
    0%   { transform: translateX(0); opacity: 0; }
    20%  { transform: translateX(-8px); opacity: 1; }
    40%  { transform: translateX(6px); }
    60%  { transform: translateX(-4px); }
    80%  { transform: translateX(2px); }
    100% { transform: translateX(0); }
}
.error-icon { color: var(--error-text); flex-shrink: 0; margin-top: 1px; }
.error-content { flex: 1; }
.error-title { font-size: 12px; font-weight: 600; color: var(--error-title); margin-bottom: 2px; }
.error-msg   { font-size: 11px; color: var(--error-text); }
.error-close {
    background: none; border: none; color: var(--error-text); cursor: pointer;
    font-size: 13px; padding: 0; opacity: 0.6; transition: .2s;
}
.error-close:hover { opacity: 1; }

/* ===== INFO BOX ===== */
.info-box {
    display: flex; align-items: flex-start; gap: 10px;
    background: var(--info-bg); border: 1px solid var(--info-border);
    border-radius: 10px; padding: 11px 13px; margin-bottom: 18px;
    animation: fadeUp 0.4s ease; transition: all 0.3s;
}
.info-icon { color: var(--info-text); flex-shrink: 0; margin-top: 1px; }
.info-content { flex: 1; }
.info-msg { font-size: 12px; font-weight: 500; color: var(--info-text); }
.info-close {
    background: none; border: none; color: var(--info-text); cursor: pointer;
    font-size: 13px; padding: 0; opacity: 0.6; transition: .2s;
}
.info-close:hover { opacity: 1; }

/* ===== FIELDS ===== */
.field { margin-bottom: 16px; }
.field label {
    display: flex; align-items: center; gap: 5px;
    font-size: 11px; font-weight: 600; color: var(--text-secondary);
    margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.04em; transition: color 0.3s;
}
.field label svg { color: var(--text-muted); }
.field input, .password-wrap input {
    width: 100%; padding: 12px 14px;
    border-radius: 10px;
    border: 1.5px solid var(--input-border);
    font-size: 13px; color: var(--text-primary);
    background: var(--input-bg);
    transition: all .25s;
}
.field input::placeholder, .password-wrap input::placeholder { color: var(--text-dim); }
.field input:focus, .password-wrap input:focus {
    outline: none; border-color: #d4af37;
    box-shadow: 0 0 0 3px rgba(212,175,55,0.12);
    background: var(--input-focus-bg);
}
.field input.input-error { border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,0.08); }

.password-wrap { position: relative; }
.password-wrap input { padding-right: 44px; }
.toggle-pass {
    position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
    background: none; border: none; cursor: pointer;
    color: var(--text-muted); padding: 4px; transition: color .2s;
}
.toggle-pass:hover { color: #d4af37; }

/* ===== SUBMIT ===== */
.submit-btn {
    width: 100%; margin-top: 4px; padding: 13px;
    border-radius: 12px; border: none;
    background: linear-gradient(135deg, #d4af37, #b8962e);
    font-weight: 700; font-size: 13px; color: #1f2937;
    cursor: pointer; transition: all .3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex; align-items: center; justify-content: center; gap: 8px;
    box-shadow: 0 4px 18px rgba(212,175,55,0.2);
}
.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(212,175,55,0.3);
    filter: brightness(1.05);
}
.submit-btn:active { transform: translateY(0); }
.submit-btn svg { color: #1f2937; }

/* ===== FOOTER ===== */
.login-left footer { margin-top: 28px; text-align: center; }
.footer-line { width: 36px; height: 1px; background: var(--footer-line); margin: 0 auto 8px; transition: background 0.3s; }
.login-left footer p { font-size: 10px; color: var(--text-dim); transition: color 0.3s; }

/* ============= RIGHT PANEL (always dark) ============= */
.login-right {
    background: linear-gradient(145deg, var(--right-bg) 0%, var(--right-bg2) 100%);
    color: #ffffff;
    padding: 44px;
    display: flex; align-items: center; justify-content: center;
    position: relative; overflow: hidden;
    border-left: 1px solid var(--right-border);
}
.right-content { position: relative; z-index: 2; }

.company-badge {
    display: inline-block; font-size: 9px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.15em;
    color: #d4af37;
    background: rgba(212,175,55,0.1);
    border: 1px solid rgba(212,175,55,0.15);
    padding: 4px 10px; border-radius: 5px; margin-bottom: 18px;
}
.company-title {
    font-size: 34px; font-weight: 800;
    line-height: 1.1; margin-bottom: 14px; letter-spacing: -0.02em;
}
.company-title span { display: block; color: #e2e8f0; }
.company-title .highlight {
    background: linear-gradient(135deg, #d4af37, #f5e6b3);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
}
.company-desc {
    font-size: 12px; color: #64748b;
    max-width: 300px; line-height: 1.6; margin-bottom: 24px;
}

.feature-cards { display: flex; gap: 8px; margin-bottom: 20px; }
.feature-card {
    background: var(--feature-bg);
    border: 1px solid var(--feature-border);
    border-radius: 12px; padding: 12px 14px;
    text-align: center; min-width: 78px;
    backdrop-filter: blur(4px); transition: all .3s ease;
}
.feature-card:hover {
    transform: translateY(-3px);
    background: var(--feature-hover-bg);
    border-color: var(--feature-hover-border);
}
.fc-icon { font-size: 16px; margin-bottom: 5px; }
.feature-card strong { display: block; font-size: 13px; color: #e2e8f0; }
.feature-card span { font-size: 10px; color: #64748b; }

.trust-indicators { display: flex; gap: 14px; }
.trust-item { display: flex; align-items: center; gap: 5px; font-size: 10px; color: #64748b; }
.trust-item svg { color: #d4af37; }

.right-bg-circles { position: absolute; inset: 0; z-index: 1; pointer-events: none; }
.circle { position: absolute; border-radius: 50%; border: 1px solid rgba(212,175,55,0.05); }
.c1 { width: 260px; height: 260px; top: -70px; right: -70px; }
.c2 { width: 180px; height: 180px; bottom: -30px; left: -30px; border-color: rgba(59,130,246,0.04); }
.c3 { width: 130px; height: 130px; top: 40%; left: 60%; border-color: rgba(212,175,55,0.03); }

/* ============= RESPONSIVE ============= */
@media (max-width: 768px) {
    .login-card { grid-template-columns: 1fr; max-width: 440px; }
    .login-right { display: none; }
    .login-left { padding: 32px 24px; }
}
</style>
@endsection
