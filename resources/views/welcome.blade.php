<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArBif Management System — Mwanza, Tanzania</title>
    <link rel="icon" href="{{ asset('images/arbifA.png') }}" type="image/png">

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/all.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* ── Brand tokens ── */
        :root {
            --navy:        #0C447C;
            --navy-dark:   #083460;
            --navy-hover:  #185FA5;
            --accent:      #5DCAA5;
            --accent-dim:  rgba(93,202,165,0.12);
            --text-light:  rgba(255,255,255,0.70);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: #f0f4f8;
            color: #1e293b;
        }

        /* ══════════════════════════════════════════
           NAVBAR
        ══════════════════════════════════════════ */
        .site-nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 64px;
            background: rgba(12,68,124,0.97);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            z-index: 1000;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .nav-brand-icon {
            width: 38px; height: 38px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.20);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }

        .nav-brand-icon img { width: 24px; height: 24px; object-fit: contain; }

        .nav-brand-text {
            display: flex; flex-direction: column;
        }

        .nav-brand-text strong {
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            line-height: 1.2;
        }

        .nav-brand-text small {
            color: var(--text-light);
            font-size: 10px;
        }

        .nav-login-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 22px;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s ease, transform 0.15s ease;
        }

        .nav-login-btn:hover {
            background: #4ab894;
            color: #fff;
            transform: translateY(-1px);
        }

        /* ══════════════════════════════════════════
           HERO
        ══════════════════════════════════════════ */
        .hero {
            min-height: 100vh;
            background: linear-gradient(145deg, var(--navy-dark) 0%, var(--navy) 55%, #1a6bb5 100%);
            display: flex;
            align-items: center;
            padding-top: 64px;
            position: relative;
            overflow: hidden;
        }

        /* Geometric background shapes */
        .hero::before {
            content: '';
            position: absolute;
            top: -120px; right: -120px;
            width: 500px; height: 500px;
            border-radius: 50%;
            background: rgba(93,202,165,0.08);
            pointer-events: none;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -100px; left: -80px;
            width: 380px; height: 380px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            padding: 80px 0;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--accent-dim);
            border: 1px solid rgba(93,202,165,0.35);
            color: var(--accent);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 24px;
        }

        .hero h1 {
            color: #fff;
            font-size: clamp(32px, 5vw, 52px);
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .hero h1 span {
            color: var(--accent);
        }

        .hero-sub {
            color: var(--text-light);
            font-size: 16px;
            line-height: 1.7;
            max-width: 520px;
            margin-bottom: 16px;
        }

        .hero-meta {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 36px;
        }

        .hero-meta-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-light);
            font-size: 13px;
        }

        .hero-meta-item i {
            color: var(--accent);
            font-size: 14px;
            width: 16px;
        }

        .hero-cta {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            background: var(--accent);
            color: #fff;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s ease, transform 0.15s ease, box-shadow 0.2s ease;
            box-shadow: 0 4px 20px rgba(93,202,165,0.35);
        }

        .hero-cta:hover {
            background: #4ab894;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(93,202,165,0.45);
        }

        /* Stats strip */
        .hero-stats {
            display: flex;
            gap: 36px;
            margin-top: 52px;
            padding-top: 36px;
            border-top: 1px solid rgba(255,255,255,0.10);
        }

        .stat-item strong {
            display: block;
            color: #fff;
            font-size: 26px;
            font-weight: 700;
        }

        .stat-item span {
            color: var(--text-light);
            font-size: 12px;
        }

        /* Hero visual card */
        .hero-visual {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hero-card {
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.14);
            border-radius: 20px;
            padding: 32px;
            width: 100%;
            max-width: 360px;
            backdrop-filter: blur(8px);
        }

        .hero-card-title {
            color: rgba(255,255,255,0.55);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 20px;
        }

        .module-pill {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.10);
            border-radius: 10px;
            margin-bottom: 10px;
            transition: background 0.2s;
        }

        .module-pill:last-child { margin-bottom: 0; }

        .module-pill:hover { background: rgba(255,255,255,0.10); }

        .pill-icon {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: var(--accent-dim);
            border: 1px solid rgba(93,202,165,0.25);
            display: flex; align-items: center; justify-content: center;
            color: var(--accent);
            font-size: 16px;
            flex-shrink: 0;
        }

        .pill-text strong {
            display: block;
            color: #fff;
            font-size: 13px;
            font-weight: 500;
        }

        .pill-text small {
            color: rgba(255,255,255,0.45);
            font-size: 11px;
        }

        /* ══════════════════════════════════════════
           FEATURES
        ══════════════════════════════════════════ */
        .features {
            padding: 96px 0;
            background: #fff;
        }

        .section-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--navy);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 12px;
        }

        .section-label::before {
            content: '';
            width: 20px; height: 2px;
            background: var(--accent);
            border-radius: 2px;
        }

        .section-title {
            font-size: clamp(24px, 3vw, 36px);
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
        }

        .section-sub {
            color: #64748b;
            font-size: 15px;
            max-width: 480px;
        }

        .feature-card {
            background: #f8fafc;
            border: 1px solid #e5eaf0;
            border-radius: 16px;
            padding: 32px 28px;
            height: 100%;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(12,68,124,0.10);
            border-color: rgba(93,202,165,0.40);
        }

        .feature-icon-wrap {
            width: 52px; height: 52px;
            border-radius: 14px;
            background: var(--accent-dim);
            border: 1px solid rgba(93,202,165,0.25);
            display: flex; align-items: center; justify-content: center;
            color: var(--navy);
            font-size: 22px;
            margin-bottom: 20px;
        }

        .feature-card h5 {
            font-size: 16px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 10px;
        }

        .feature-card p {
            font-size: 13px;
            color: #64748b;
            line-height: 1.7;
            margin: 0;
        }

        /* ══════════════════════════════════════════
           CTA BAND
        ══════════════════════════════════════════ */
        .cta-band {
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-hover) 100%);
            padding: 72px 0;
            text-align: center;
        }

        .cta-band h2 {
            color: #fff;
            font-size: clamp(22px, 3vw, 32px);
            font-weight: 700;
            margin-bottom: 12px;
        }

        .cta-band p {
            color: var(--text-light);
            font-size: 15px;
            margin-bottom: 32px;
        }

        /* ══════════════════════════════════════════
           FOOTER
        ══════════════════════════════════════════ */
        .site-footer {
            background: #0a0f1a;
            color: rgba(255,255,255,0.55);
            padding: 40px 0;
        }

        .footer-brand strong { color: #fff; font-size: 15px; }
        .footer-brand p { font-size: 12px; margin-top: 6px; line-height: 1.6; }

        .footer-right {
            text-align: right;
            font-size: 12px;
        }

        /* ══════════════════════════════════════════
           RESPONSIVE
        ══════════════════════════════════════════ */
        @media (max-width: 991px) {
            .hero-visual { margin-top: 48px; }
            .site-nav { padding: 0 20px; }
            .hero-stats { gap: 24px; flex-wrap: wrap; }
        }

        @media (max-width: 575px) {
            .hero-stats { gap: 20px; }
            .stat-item strong { font-size: 22px; }
        }
    </style>
</head>
<body>

    {{-- ══════════════════════════════════════════
         NAVBAR
    ══════════════════════════════════════════ --}}
    <nav class="site-nav">
        <a href="#" class="nav-brand">
            <div class="nav-brand-icon">
                <img src="{{ asset('images/arbifA.png') }}" alt="ArBif">
            </div>
            <div class="nav-brand-text">
                <strong>ArBif</strong>
                <small>Management System</small>
            </div>
        </a>
        <a href="{{ route('login') }}" class="nav-login-btn">
            <i class="bi bi-box-arrow-in-right"></i> Sign In
        </a>
    </nav>

    {{-- ══════════════════════════════════════════
         HERO
    ══════════════════════════════════════════ --}}
    <section class="hero" id="home">
        <div class="container hero-content">
            <div class="row align-items-center">

                <div class="col-lg-7">
                    <div class="hero-badge">
                        <i class="bi bi-shield-check"></i> Secure Loan Management
                    </div>
                    <h1>Modern <span>Loan Management</span> for Financial Operations</h1>
                    <p class="hero-sub">
                        A complete platform for managing group loans, client records,
                        daily collections, payroll, and branch reporting — built for
                        modern microfinance operations.
                    </p>
                    <div class="hero-meta">
                        <div class="hero-meta-item">
                            <i class="bi bi-geo-alt-fill"></i>
                            Mkolani Street, Mwanza, Tanzania
                        </div>
                        <div class="hero-meta-item">
                            <i class="bi bi-building"></i>
                            ArBif Financial Services
                        </div>
                    </div>
                    <a href="{{ route('login') }}" class="hero-cta">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Access the System
                    </a>

                    <div class="hero-stats">
                        <div class="stat-item">
                            <strong>3</strong>
                            <span>Core Modules</span>
                        </div>
                        <div class="stat-item">
                            <strong>Role</strong>
                            <span>Based Access</span>
                        </div>
                        <div class="stat-item">
                            <strong>Real-time</strong>
                            <span>Reporting</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 hero-visual">
                    <div class="hero-card">
                        <div class="hero-card-title">System Modules</div>

                        <div class="module-pill">
                            <div class="pill-icon"><i class="bi bi-sliders"></i></div>
                            <div class="pill-text">
                                <strong>Configuration</strong>
                                <small>Users, roles &amp; system settings</small>
                            </div>
                        </div>

                        <div class="module-pill">
                            <div class="pill-icon"><i class="bi bi-briefcase"></i></div>
                            <div class="pill-text">
                                <strong>Working Side</strong>
                                <small>Loans, clients &amp; daily operations</small>
                            </div>
                        </div>

                        <div class="module-pill">
                            <div class="pill-icon"><i class="bi bi-bar-chart-line"></i></div>
                            <div class="pill-text">
                                <strong>Reporting Side</strong>
                                <small>Analytics &amp; branch reports</small>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════
         FEATURES
    ══════════════════════════════════════════ --}}
    <section class="features" id="features">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-6">
                    <div class="section-label">What We Offer</div>
                    <h2 class="section-title">Everything you need to manage loans efficiently</h2>
                    <p class="section-sub">From client registration to final repayment — all in one place.</p>
                </div>
            </div>
            <div class="row g-4">

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrap">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h5>Group & Client Registration</h5>
                        <p>Register new clients and groups quickly. Manage member details,
                           guarantors, and documentation in a single workflow.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrap">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <h5>Loan Disbursement & Repayments</h5>
                        <p>Issue loans with configurable terms, track daily repayments,
                           and get instant visibility into outstanding balances.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrap">
                            <i class="bi bi-bar-chart-line-fill"></i>
                        </div>
                        <h5>Reports &amp; Analytics</h5>
                        <p>Generate daily, weekly, monthly, and annual reports.
                           Monitor branch performance with clear visual dashboards.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrap">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <h5>Role-Based Access Control</h5>
                        <p>Assign precise permissions per role — admin, loan officer,
                           finance, HR, and more. Every user sees only what they need.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrap">
                            <i class="bi bi-person-lines-fill"></i>
                        </div>
                        <h5>HR &amp; Payroll Management</h5>
                        <p>Manage employee records, salary levels, weekly allowances,
                           and payroll payments all within the same system.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrap">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <h5>Audit Trail &amp; Session Security</h5>
                        <p>Encrypted audit logs and automatic session timeout keep
                           your financial data safe and fully traceable.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════
         CTA BAND
    ══════════════════════════════════════════ --}}
    <section class="cta-band">
        <div class="container">
            <h2>Ready to get started?</h2>
            <p>Sign in to access your dashboard and manage your operations.</p>
            <a href="{{ route('login') }}" class="hero-cta" style="display:inline-flex;">
                <i class="bi bi-box-arrow-in-right"></i>
                Sign In to ArBif
            </a>
        </div>
    </section>

    {{-- ══════════════════════════════════════════
         FOOTER
    ══════════════════════════════════════════ --}}
    <footer class="site-footer">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 footer-brand">
                    <strong>ArBif Management System</strong>
                    <p>Mkolani Street, Mwanza, Tanzania<br>
                       Empowering financial operations for communities.</p>
                </div>
                <div class="col-md-6 footer-right">
                    &copy; {{ date('Y') }} ArBif Management System.<br>All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

</body>
</html>