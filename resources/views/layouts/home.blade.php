<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArBif Management System</title>

    <link rel="icon" href="{{ asset('images/arbifA.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('images/arbifA.png') }}">

    {{-- Bootstrap & Icons --}}
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* ── Brand tokens ── */
        :root {
            --navy:   #0C447C;
            --blue:   #185FA5;
            --accent: #5DCAA5;   /* logo green */
            --light:  #f0f6ff;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            min-height: 100vh;
            background: var(--navy);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
            position: relative;
            overflow: hidden;
        }

        /* Subtle geometric background */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 70% 55% at 15% 20%, rgba(93,202,165,0.12) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 85% 80%, rgba(24,95,165,0.25) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        /* ── Header ── */
        .hub-header {
            position: relative;
            z-index: 1;
            text-align: center;
            margin-bottom: 40px;
        }

        .hub-logo {
            width: 68px;
            height: 68px;
            background: rgba(255,255,255,0.10);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .hub-logo img {
            width: 42px;
            height: 42px;
            object-fit: contain;
        }

        .hub-header h1 {
            color: #fff;
            font-size: 22px;
            font-weight: 600;
            letter-spacing: 0.01em;
        }

        .hub-header p {
            color: rgba(255,255,255,0.50);
            font-size: 13px;
            margin-top: 4px;
        }

        /* ── Divider ── */
        .hub-divider {
            width: 40px;
            height: 2px;
            background: var(--accent);
            border-radius: 2px;
            margin: 12px auto 0;
        }

        /* ── Module cards grid ── */
        .hub-grid {
            position: relative;
            z-index: 1;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            width: 100%;
            max-width: 900px;
        }

        /* Each module card */
        .hub-card {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 16px;
            width: 240px;
            padding: 32px 24px 28px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .hub-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: var(--accent);
            border-radius: 16px 16px 0 0;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .hub-card:hover {
            transform: translateY(-4px);
            background: rgba(255,255,255,0.11);
            border-color: rgba(93,202,165,0.35);
        }

        .hub-card:hover::before {
            opacity: 1;
        }

        .hub-card:active {
            transform: translateY(-1px);
        }

        /* Icon circle */
        .card-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: rgba(93,202,165,0.15);
            border: 1px solid rgba(93,202,165,0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: var(--accent);
        }

        .card-label {
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            text-align: center;
            line-height: 1.3;
        }

        .card-sub {
            color: rgba(255,255,255,0.45);
            font-size: 12px;
            text-align: center;
            line-height: 1.5;
        }

        /* Hidden form submit button — cards trigger it */
        .hub-card button {
            display: none;
        }

        /* ── Footer ── */
        .hub-footer {
            position: relative;
            z-index: 1;
            margin-top: 40px;
            color: rgba(255,255,255,0.30);
            font-size: 12px;
            text-align: center;
        }

        /* ── Responsive ── */
        @media (max-width: 560px) {
            .hub-card { width: 100%; max-width: 320px; }
        }
    </style>
</head>
<body>
    @include('sweetalert::alert')

    {{-- ── Header ── --}}
    <header class="hub-header">
        <div class="hub-logo">
            <img src="{{ asset('images/arbifA.png') }}" alt="ArBif Logo">
        </div>
        <h1>ArBif Management System</h1>
        <p>Select a module to continue</p>
        <div class="hub-divider"></div>
    </header>

    {{-- ── Module selector ── --}}
    <form method="POST" action="{{ route('settings') }}" id="module-form">
        @csrf
        <div class="hub-grid">

            @can('view-confirguration-side')
            <div class="hub-card" onclick="submitModule('configuration')">
                <div class="card-icon">
                    <i class="bi bi-sliders"></i>
                </div>
                <span class="card-label">Configuration Side</span>
                <span class="card-sub">System settings, roles &amp; access control</span>
                <button type="submit" name="module" value="configuration"></button>
            </div>
            @endcan

            @can('view-working-side')
            <div class="hub-card" onclick="submitModule('working')">
                <div class="card-icon">
                    <i class="bi bi-briefcase"></i>
                </div>
                <span class="card-label">Working Side</span>
                <span class="card-sub">Day-to-day loan operations &amp; transactions</span>
                <button type="submit" name="module" value="working"></button>
            </div>
            @endcan

            @can('view-reporting-side')
            <div class="hub-card" onclick="submitModule('reports')">
                <div class="card-icon">
                    <i class="bi bi-bar-chart-line"></i>
                </div>
                <span class="card-label">Reporting Side</span>
                <span class="card-sub">Analytics, statements &amp; branch reports</span>
                <button type="submit" name="module" value="reports"></button>
            </div>
            @endcan

        </div>
    </form>

    {{-- ── Footer ── --}}
    <footer class="hub-footer">
        &copy; {{ date('Y') }} ArBif Management System. All rights reserved.
    </footer>

    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('js/sweetalert-custom.js') }}"></script>
    <script src="{{ asset('js/sweetalert-ajax.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        function submitModule(value) {
            // Find the hidden button with this value and click it
            var btn = document.querySelector('button[value="' + value + '"]');
            if (btn) btn.click();
        }
    </script>

    @stack('scripts')
</body>
</html>