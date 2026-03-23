@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="login-page">

    <div class="login-card">

        <!-- ================= LEFT : LOGIN FORM ================= -->
        <div class="login-left">

            <div class="brand">
                <div class="brand-icon">
                    <img src="{{ asset('images/logoPTGoldenIB.ico') }}" alt="PT Golden IB">
                </div>
                <span>ERP System</span>
            </div>

            <h1 class="title">Sign In</h1>
            <p class="subtitle">
                Akses internal sistem ERP PT Golden Intan Berlian
            </p>

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <label>Email</label>
                <input
                    type="email"
                    name="email"
                    placeholder="admin@goldenib.co.id"
                    required
                    autofocus>

                <label>Password</label>
                <input
                    type="password"
                    name="password"
                    placeholder="••••••••"
                    required>

                <button type="submit">
                    Sign In
                </button>
            </form>

            <footer>
                © {{ date('Y') }} PT Golden Intan Berlian
            </footer>

        </div>

        <!-- ================= RIGHT : COMPANY PANEL ================= -->
        <div class="login-right">

            <div class="company-title">
                <span>PT</span>
                <span>Golden</span>
                <span>IB</span>
            </div>

            <p class="company-desc">
                Sistem ERP internal terintegrasi untuk mendukung operasional,
                monitoring, dan pengambilan keputusan strategis perusahaan.
            </p>

            <div class="feature-cards">
                <div class="feature-card">
                    <strong>ERP</strong>
                    <span>System</span>
                </div>
                <div class="feature-card">
                    <strong>Admin</strong>
                    <span>Dashboard</span>
                </div>
                <div class="feature-card">
                    <strong>Reports</strong>
                    <span>Analytics</span>
                </div>
            </div>

        </div>

    </div>

</div>

<style>
/* ================= RESET ================= */
.login-page * {
    box-sizing: border-box;
    font-family: 'Inter', system-ui, sans-serif;
}

/* ================= PAGE ================= */
.login-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background:
        radial-gradient(circle at left, #eef6ff, transparent 45%),
        radial-gradient(circle at right, #fdf6e3, transparent 45%),
        #f8fafc;
    padding: 24px;
}

/* ================= CARD ================= */
.login-card {
    width: 100%;
    max-width: 900px;
    background: #ffffff;
    border-radius: 26px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    overflow: hidden;
    box-shadow: 0 30px 80px rgba(0,0,0,.18);
    animation: fadeUp .6s ease;
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ================= LEFT ================= */
.login-left {
    padding: 52px;
}

.brand {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 36px;
}

.brand-icon {
    width: 46px;
    height: 46px;
    background: linear-gradient(135deg, #f5e6b3, #d4af37);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.brand-icon img {
    width: 24px;
}

.brand span {
    font-weight: 600;
    color: #1f2937;
}

.title {
    font-size: 30px;
    color: #1f2937;
    margin-bottom: 6px;
}

.subtitle {
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 32px;
}

.login-left label {
    font-size: 13px;
    color: #374151;
    margin-top: 18px;
    display: block;
}

.login-left input {
    width: 100%;
    padding: 13px 15px;
    border-radius: 12px;
    border: 1.5px solid #e5e7eb;
    font-size: 14px;
    transition: .25s;
}

.login-left input:focus {
    outline: none;
    border-color: #d4af37;
    box-shadow: 0 0 0 4px rgba(212,175,55,.25);
}

.login-left button {
    width: 100%;
    margin-top: 30px;
    padding: 14px;
    border-radius: 14px;
    border: none;
    background: linear-gradient(135deg, #f5e6b3, #d4af37);
    font-weight: 600;
    cursor: pointer;
    transition: .25s;
}

.login-left button:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 26px rgba(212,175,55,.35);
}

.login-left footer {
    margin-top: 38px;
    font-size: 12px;
    color: #9ca3af;
    text-align: center;
}

/* ================= RIGHT ================= */
.login-right {
    background: linear-gradient(160deg, #d4af37, #b8962e);
    color: #ffffff;
    padding: 52px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.company-title {
    font-size: 42px;
    font-weight: 700;
    line-height: 1.05;
    margin-bottom: 20px;
}

.company-title span {
    display: block;
}

.company-desc {
    font-size: 14px;
    max-width: 320px;
    opacity: .95;
    margin-bottom: 40px;
}

/* ================= FEATURE CARDS ================= */
.feature-cards {
    display: flex;
    gap: 18px;
}

.feature-card {
    background: rgba(255,255,255,.16);
    border-radius: 14px;
    padding: 14px 16px;
    text-align: center;
    min-width: 90px;
    backdrop-filter: blur(4px);
    transition: .25s;
}

.feature-card:hover {
    transform: translateY(-6px);
    background: rgba(255,255,255,.22);
}

.feature-card strong {
    display: block;
    font-size: 18px;
}

.feature-card span {
    font-size: 12px;
    opacity: .9;
}

/* ================= RESPONSIVE ================= */
@media (max-width: 768px) {
    .login-card {
        grid-template-columns: 1fr;
    }
    .login-right {
        display: none;
    }
}
</style>
@endsection
