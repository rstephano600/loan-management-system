<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mfumo wa Mikopo - Mkolani Street, Mwanza</title>
    <link rel="icon" href="{{ asset('images/arbifA.png') }}" type="image/png">
    <!-- Bootstrap 5.3 CSS (Local) -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap CSS -->
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">

<!-- Font Awesome -->
<link href="{{ asset('assets/css/all.min.css') }}" rel="stylesheet">

<!-- Select2 CSS -->
<link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">

<!-- Your custom CSS if needed -->
<link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">

    <link href="{{ asset('css/web-app.css') }}" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .login-btn {
            background-color: #28a745;
            border-color: #28a745;
            padding: 10px 30px;
            font-weight: bold;
        }
        .features-section {
            padding: 80px 0;
            background-color: #f8f9fa;
        }
        .feature-icon {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Sehemu ya Kwanza (Hero Section) -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-4">Karibu kwenye Mfumo wa Mikopo</h1>
                    <p class="lead mb-4">Huduma bora za kifedha kwa wafanya biashara wadogo na wakati</p>
                    <p class="mb-4">
                        <strong>Anwani:</strong> Mkolani Street, Mwanza, Tanzania<br>
                        <strong>Lengo:</strong> Kukuza uwezo wa kifedha wa wananchi
                    </p>
                    <a href="{{ route('login') }}" class="btn btn-success btn-lg login-btn">
                        Ingia kwenye Mfumo
                    </a>
                </div>
                <div class="col-lg-4">
                    <div class="text-center">
                        <svg width="200" height="200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="feature-icon">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sehemu ya Huduma (Features) -->
    <section class="features-section">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-12">
                    <h2 class="display-5 mb-4">Huduma Zetu</h2>
                    <p class="lead">Tunatoa mikopo na usaidizi wa kifedha kwa vikundi</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="text-center">
                        <div class="feature-icon">
                            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <h4>Usajili wa Vikundi</h4>
                        <p>Kusajili vikundi vipya na wanachama kwa urahisi</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="text-center">
                        <div class="feature-icon">
                            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                            </svg>
                        </div>
                        <h4>Mikopo na Malipo</h4>
                        <p>Kutoa mikopo na kufuatilia malipo kila siku</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="text-center">
                        <div class="feature-icon">
                            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="7.5 4.21 12 6.81 16.5 4.21"></polyline>
                                <polyline points="7.5 19.79 7.5 14.6 3 12"></polyline>
                                <polyline points="21 12 16.5 14.6 16.5 19.79"></polyline>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                        </div>
                        <h4>Ripoti na Grafu</h4>
                        <p>Uundaji wa ripoti za siku, wiki, mwezi na mwaka</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sehemu ya Mwisho (Footer) -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Mfumo wa Mikopo</h5>
                    <p>Mkolani Street, Mwanza<br>Tanzania</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; {{ date('Y') }} Mfumo wa Mikopo. Haki zote zimehifadhiwa.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JavaScript -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <!-- Bootstrap JS -->
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

<!-- Select2 JS -->
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
</body>
</html>