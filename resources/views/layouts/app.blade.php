@php
    $languages = \App\Support\Locales::languages();
    $activeLanguages = \App\Support\Locales::active();
    $contactEmail = \App\Models\Setting::get('contact_email');
    $socials = [
        ['url' => \App\Models\Setting::get('linkedin_url'), 'icon' => 'fab fa-linkedin-in'],
        ['url' => \App\Models\Setting::get('instagram_url'), 'icon' => 'fab fa-instagram'],
        ['url' => \App\Models\Setting::get('facebook_url'), 'icon' => 'fab fa-facebook-f'],
    ];
    $socials = array_values(array_filter($socials, fn (array $social) => filled($social['url'])));
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <title>{{ $page->t('meta_title') }}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    @if ($page->t('meta_keywords'))
    <meta name="keywords" content="{{ $page->t('meta_keywords') }}">
    @endif
    <meta name="description" content="{{ $page->t('meta_description') }}">
    @if ($page->noindex)
    <meta name="robots" content="noindex">
    @endif
    @foreach ($activeLanguages as $language)
    <link rel="alternate" hreflang="{{ $language->code }}" href="{{ locale_url($language->code) }}">
    @endforeach

    <!-- Favicon -->
    <link href="{{ asset('img/favicon.ico') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    @if (app()->getLocale() === 'zh')
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@400;500;700&display=swap" rel="stylesheet">
    @endif

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/owlcarousel/assets/owl.theme.default.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- Sabonea Brand Stylesheet -->
    <link href="{{ asset('css/sabonea.css') }}" rel="stylesheet">
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show sabonea-loader position-fixed w-100 vh-100 top-0 start-0 d-flex flex-column align-items-center justify-content-center">
        <img src="{{ asset('img/sabonea/logo-sabonea-white.png') }}" alt="Sabonea" class="sabonea-loader-logo">
        <div class="sabonea-loader-dots">
            <span></span><span></span><span></span>
        </div>
    </div>
    <!-- Spinner End -->


    <!-- Topbar Start -->
    <div class="container-fluid topbar-sabonea text-white p-0">
        <div class="row gx-0 d-none d-lg-flex">
            <div class="col-lg-7 px-5 text-start">
                @if ($contactEmail)
                <div class="h-100 d-inline-flex align-items-center me-4">
                    <small class="fa fa-envelope text-sabonea-orange me-2"></small>
                    <small><a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a></small>
                </div>
                @endif
            </div>
            <div class="col-lg-5 px-5 text-end">
                @if ($activeLanguages->count() > 1)
                <div class="h-100 d-inline-flex align-items-center me-4 topbar-langs">
                    @foreach ($activeLanguages as $language)
                    <a href="{{ locale_url($language->code) }}" hreflang="{{ $language->code }}" @class(['active' => $language->code === app()->getLocale()])>{{ strtoupper($language->code) === 'ZH' ? '中文' : strtoupper($language->code) }}</a>
                    @endforeach
                </div>
                @endif
                <div class="h-100 d-inline-flex align-items-center me-4">
                    <small>{{ ui('layout.follow_us') }}</small>
                </div>
                <div class="h-100 d-inline-flex align-items-center mx-n2">
                    @foreach ($socials as $social)
                    <a @class(['btn btn-square btn-link', 'border-0 border-end border-secondary' => ! $loop->last]) target="_blank" rel="noopener" href="{{ $social['url'] }}"><i class="{{ $social['icon'] }}"></i></a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-xl bg-white navbar-light p-0">
        <a href="{{ route('accueil') }}" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <img src="{{ asset('img/sabonea/logo-sabonea.png') }}" alt="{{ ui('layout.logo_alt') }}">
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav mx-auto p-4 p-xl-0">
                <a href="{{ route('accueil') }}" class="nav-item nav-link{{ request()->routeIs('accueil') ? ' active' : '' }}">{{ ui('nav.home') }}</a>
                <a href="{{ route('a-propos') }}" class="nav-item nav-link{{ request()->routeIs('a-propos') ? ' active' : '' }}">{{ ui('nav.about') }}</a>
                <a href="{{ route('comment-ca-fonctionne') }}" class="nav-item nav-link{{ request()->routeIs('comment-ca-fonctionne', 'fournisseur-exemple') ? ' active' : '' }}">{{ ui('nav.how') }}</a>
                <a href="{{ route('secteurs') }}" class="nav-item nav-link{{ request()->routeIs('secteurs') ? ' active' : '' }}">{{ ui('nav.sectors') }}</a>
                <a href="{{ route('pourquoi-sabonea') }}" class="nav-item nav-link{{ request()->routeIs('pourquoi-sabonea') ? ' active' : '' }}">{{ ui('nav.why') }}</a>
                <a href="{{ route('contact') }}" class="nav-item nav-link{{ request()->routeIs('contact') ? ' active' : '' }}">{{ ui('nav.contact') }}</a>
            </div>
            <div class="navbar-phone px-xl-3 d-flex align-items-center">
                <a href="{{ route('contact') }}" class="navbar-secondary-link">{{ ui('nav.become_supplier') }}</a>
                <a href="{{ route('expression-de-besoin') }}" class="btn navbar-cta py-2 px-3">{{ ui('nav.express_need') }}</a>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->


    @yield('content')


    <!-- Footer Start -->
    <div class="container-fluid footer-sabonea text-light footer pt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-4 col-md-6">
                    <img src="{{ asset('img/sabonea/logo-sabonea-white.png') }}" alt="Sabonea" style="height:50px;" class="mb-3">
                    <p class="mb-3">{{ ui('footer.tagline') }}</p>
                    @if ($contactEmail)
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i><a href="mailto:{{ $contactEmail }}" class="text-white-50">{{ $contactEmail }}</a></p>
                    @endif
                    <div class="d-flex pt-2">
                        @foreach ($socials as $social)
                        <a class="btn btn-square btn-primary me-2" target="_blank" rel="noopener" href="{{ $social['url'] }}"><i class="{{ $social['icon'] }}"></i></a>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h3 class="text-white text-uppercase mb-4">{{ ui('footer.navigation') }}</h3>
                    <a class="btn btn-link" href="{{ route('a-propos') }}">{{ ui('nav.about') }}</a>
                    <a class="btn btn-link" href="{{ route('comment-ca-fonctionne') }}">{{ ui('footer.how') }}</a>
                    <a class="btn btn-link" href="{{ route('secteurs') }}">{{ ui('footer.sectors') }}</a>
                    <a class="btn btn-link" href="{{ route('pourquoi-sabonea') }}">{{ ui('footer.why') }}</a>
                    <a class="btn btn-link" href="{{ route('expression-de-besoin') }}">{{ ui('nav.express_need') }}</a>
                    <a class="btn btn-link" href="{{ route('contact') }}">{{ ui('nav.contact') }}</a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h3 class="text-white text-uppercase mb-4">{{ ui('footer.languages') }}</h3>
                    <p class="mb-3">{{ ui('footer.languages_text') }}</p>
                    <div>
                        @foreach ($languages as $language)
                        @if ($language->code === app()->getLocale())
                        <span class="lang-pill active">{{ $language->name }}</span>
                        @elseif ($language->is_active)
                        <a class="lang-pill" href="{{ locale_url($language->code) }}" hreflang="{{ $language->code }}">{{ $language->name }}</a>
                        @else
                        <span class="lang-pill upcoming">{{ $language->name }}</span>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright py-4" style="border-top: 1px solid rgba(255,255,255,.1);">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a href="{{ route('accueil') }}" class="text-white">Sabonea</a>, {{ ui('footer.rights') }}
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        {{ ui('footer.credit_before') }} <a href="https://htmlcodex.com">HTML Codex</a>{{ ui('footer.credit_after') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('js/main.js') }}"></script>
</body>

</html>
