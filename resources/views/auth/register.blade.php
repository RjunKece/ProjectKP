@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="login-page" id="loginPage">

    <button class="theme-toggle" id="themeToggle" onclick="toggleLoginTheme()" title="Ganti Tema">
        <span id="themeIcon">🌙</span>
    </button>

    <div class="login-card">

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

            <h1 class="title">Daftar Akun</h1>
            <p class="subtitle">
                Buat akun karyawan untuk mengakses portal ERP
            </p>

            @if($errors->any())
            <div class="error-box" id="errorBox">
                <div class="error-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                </div>
                <div class="error-content">
                    <p class="error-title">Registrasi Gagal</p>
                    @foreach($errors->all() as $error)
                        <p class="error-msg">{{ $error }}</p>
                    @endforeach
                </div>
                <button type="button" class="error-close" onclick="document.getElementById('errorBox').style.display='none'">✕</button>
            </div>
            @endif

            <form method="POST" action="{{ route('register.submit') }}">
                @csrf

                <div class="field">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama lengkap" required autofocus>
                </div>

                <div class="field">
                    <label>Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@perusahaan.com" required>
                </div>

                <div class="field">
                    <label>Divisi</label>
                    <select name="division_id" required class="select-input">
                        <option value="">Pilih divisi</option>
                        @foreach($divisions as $division)
                            <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>
                                {{ $division->nama_divisi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label>Password</label>
                    <div class="password-wrap">
                        <input type="password" name="password" id="passwordInput" placeholder="Min. 8 karakter" required>
                    </div>
                </div>

                <div class="field">
                    <label>Konfirmasi Password</label>
                    <div class="password-wrap">
                        <input type="password" name="password_confirmation" placeholder="Ulangi password" required>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/>
                    </svg>
                    Daftar Sekarang
                </button>
            </form>

            <p class="auth-switch">
                Sudah punya akun?
                <a href="{{ route('login') }}">Masuk di sini</a>
            </p>

            <footer>
                <div class="footer-line"></div>
                <p>&copy; {{ date('Y') }} PT Golden Intan Berlian. All rights reserved.</p>
            </footer>

        </div>

        <div class="login-right">
            <div class="right-content">
                <div class="company-badge">Employee Self Registration</div>
                <div class="company-title">
                    <span>Bergabung dengan</span>
                    <span class="highlight">Tim Kami</span>
                </div>
                <p class="company-desc">
                    Akun baru otomatis terdaftar sebagai karyawan. Pilih divisi Anda
                    untuk mengakses target kerja dan laporan yang relevan.
                </p>
            </div>
            <div class="right-bg-circles">
                <div class="circle c1"></div>
                <div class="circle c2"></div>
                <div class="circle c3"></div>
            </div>
        </div>

    </div>
</div>

@include('auth.partials.login-styles')

<script>
function toggleLoginTheme() {
    const page = document.getElementById('loginPage');
    const icon = document.getElementById('themeIcon');
    const isLight = page.classList.toggle('light-mode');
    icon.textContent = isLight ? '🌙' : '☀️';
    localStorage.setItem('erp_login_theme', isLight ? 'light' : 'dark');
}
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
@endsection
