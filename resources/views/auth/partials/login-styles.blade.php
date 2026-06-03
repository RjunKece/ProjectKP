<style>
/* ============= RESET ============= */
.login-page *, .login-page *::before, .login-page *::after {
    box-sizing: border-box;
    font-family: 'Inter', system-ui, sans-serif;
    margin: 0; padding: 0;
}

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
    --footer-line: #e2e8f0;
    --toggle-bg: rgba(0,0,0,0.04);
    --toggle-border: rgba(0,0,0,0.08);
}

.login-page {
    width: 100vw; height: 100vh;
    display: flex; align-items: center; justify-content: center;
    background: var(--page-bg);
    position: relative; overflow: hidden;
    transition: background 0.4s ease;
}

.theme-toggle {
    position: absolute; top: 24px; right: 24px; z-index: 50;
    width: 42px; height: 42px; border-radius: 12px;
    background: var(--toggle-bg);
    border: 1px solid var(--toggle-border);
    cursor: pointer; display: flex;
    align-items: center; justify-content: center;
    font-size: 18px;
}

.login-card {
    width: 100%; max-width: 960px;
    background: var(--card-bg);
    border-radius: 20px;
    display: grid; grid-template-columns: 1fr 1fr;
    overflow: hidden;
    box-shadow: 0 24px 64px var(--card-shadow), 0 0 0 1px var(--card-border);
    animation: fadeUp .5s ease;
    position: relative; z-index: 1;
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

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
}
.brand-icon img { width: 20px; }
.brand-name { font-weight: 700; font-size: 14px; color: var(--text-primary); }
.brand-sub  { font-size: 10px; color: var(--text-muted); margin-top: 1px; }

.title { font-size: 26px; font-weight: 800; color: var(--text-primary); margin-bottom: 4px; }
.subtitle { font-size: 12px; color: var(--text-muted); margin-bottom: 24px; line-height: 1.5; }

.error-box {
    display: flex; align-items: flex-start; gap: 10px;
    background: var(--error-bg); border: 1px solid var(--error-border);
    border-radius: 10px; padding: 11px 13px; margin-bottom: 18px;
}
.error-title { font-size: 12px; font-weight: 600; color: var(--error-title); }
.error-msg { font-size: 11px; color: var(--error-text); }

.info-box {
    display: flex; align-items: flex-start; gap: 10px;
    background: var(--info-bg); border: 1px solid var(--info-border);
    border-radius: 10px; padding: 11px 13px; margin-bottom: 18px;
}
.info-msg { font-size: 12px; font-weight: 500; color: var(--info-text); }

.field { margin-bottom: 16px; }
.field label {
    display: block;
    font-size: 11px; font-weight: 600; color: var(--text-secondary);
    margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.04em;
}
.field input, .password-wrap input, .select-input {
    width: 100%; padding: 12px 14px;
    border-radius: 10px;
    border: 1.5px solid var(--input-border);
    font-size: 13px; color: var(--text-primary);
    background: var(--input-bg);
}
.field input:focus, .password-wrap input:focus, .select-input:focus {
    outline: none; border-color: #d4af37;
    box-shadow: 0 0 0 3px rgba(212,175,55,0.12);
}

.password-wrap { position: relative; }

.submit-btn {
    width: 100%; margin-top: 4px; padding: 13px;
    border-radius: 12px; border: none;
    background: linear-gradient(135deg, #d4af37, #b8962e);
    font-weight: 700; font-size: 13px; color: #1f2937;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 8px;
}

.auth-switch {
    text-align: center;
    margin-top: 16px;
    font-size: 12px;
    color: var(--text-muted);
}
.auth-switch a {
    color: #d4af37;
    font-weight: 600;
    text-decoration: none;
}
.auth-switch a:hover { text-decoration: underline; }

.login-left footer { margin-top: 20px; text-align: center; }
.footer-line { width: 36px; height: 1px; background: var(--footer-line); margin: 0 auto 8px; }
.login-left footer p { font-size: 10px; color: var(--text-dim); }

.login-right {
    background: linear-gradient(145deg, var(--right-bg) 0%, var(--right-bg2) 100%);
    color: #ffffff;
    padding: 44px;
    display: flex; align-items: center; justify-content: center;
    position: relative; overflow: hidden;
    border-left: 1px solid var(--right-border);
}

.company-badge {
    display: inline-block; font-size: 9px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.15em;
    color: #d4af37;
    background: rgba(212,175,55,0.1);
    border: 1px solid rgba(212,175,55,0.15);
    padding: 4px 10px; border-radius: 5px; margin-bottom: 18px;
}
.company-title { font-size: 34px; font-weight: 800; line-height: 1.1; margin-bottom: 14px; }
.company-title span { display: block; color: #e2e8f0; }
.company-title .highlight {
    background: linear-gradient(135deg, #d4af37, #f5e6b3);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
}
.company-desc { font-size: 12px; color: #64748b; max-width: 300px; line-height: 1.6; }

.right-bg-circles { position: absolute; inset: 0; z-index: 1; pointer-events: none; }
.circle { position: absolute; border-radius: 50%; border: 1px solid rgba(212,175,55,0.05); }
.c1 { width: 260px; height: 260px; top: -70px; right: -70px; }
.c2 { width: 180px; height: 180px; bottom: -30px; left: -30px; }
.c3 { width: 130px; height: 130px; top: 40%; left: 60%; }

@media (max-width: 768px) {
    .login-card { grid-template-columns: 1fr; max-width: 440px; }
    .login-right { display: none; }
    .login-left { padding: 32px 24px; }
}
</style>
