@php
    // Pages come from the back-office; error pages have none and give $metaTitle / $metaDescription / $noindex.
    $page ??= null;
    $languages = \App\Support\Locales::languages();
    $activeLanguages = \App\Support\Locales::active();
    $contactEmail = \App\Models\Setting::get('contact_email');
    $socials = [
        ['url' => \App\Models\Setting::get('linkedin_url'), 'name' => 'LinkedIn', 'icon' => 'fab fa-linkedin-in'],
        ['url' => \App\Models\Setting::get('instagram_url'), 'name' => 'Instagram', 'icon' => 'fab fa-instagram'],
        ['url' => \App\Models\Setting::get('facebook_url'), 'name' => 'Facebook', 'icon' => 'fab fa-facebook-f'],
    ];
    $socials = array_values(array_filter($socials, fn (array $social) => filled($social['url'])));
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <title>{{ $page ? $page->t('meta_title') : ($metaTitle ?? 'Sabonea') }}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    @if ($page?->t('meta_keywords'))
    <meta name="keywords" content="{{ $page->t('meta_keywords') }}">
    @endif
    <meta name="description" content="{{ $page ? $page->t('meta_description') : ($metaDescription ?? '') }}">
    @if ($page?->noindex || ($noindex ?? false))
    <meta name="robots" content="noindex">
    @endif
    @if ($page)
    @foreach ($activeLanguages as $language)
    <link rel="alternate" hreflang="{{ $language->code }}" href="{{ locale_url($language->code) }}">
    @endforeach
    @endif

    <link href="{{ asset('img/favicon.ico') }}" rel="icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    @if (app()->getLocale() === 'zh')
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@400;500;700&display=swap" rel="stylesheet">
    @endif

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sabonea.css') }}?v={{ filemtime(public_path('css/sabonea.css')) }}" rel="stylesheet">
    @stack('styles')
</head>

<body>
    @if ($contactEmail || $socials)
    <div class="topbar d-none d-lg-block">
        <div class="topbar-inner">
            <div>
                @if ($contactEmail)
                <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
                @endif
            </div>
            @if ($socials)
            <div class="topbar-social">
                <span>{{ ui('layout.follow_us') }}</span>
                @foreach ($socials as $social)
                <a href="{{ $social['url'] }}" target="_blank" rel="noopener" aria-label="{{ $social['name'] }}"><i class="{{ $social['icon'] }}" aria-hidden="true"></i></a>
                @endforeach
            </div>
            @endif
        </div>
    </div>
    @endif

    <nav class="navbar navbar-expand-xl bg-white navbar-light p-0">
        <a href="{{ route('accueil') }}" class="navbar-brand">
            <img src="{{ asset('img/sabonea/logo-sabonea.png') }}" alt="{{ ui('layout.logo_alt') }}">
        </a>
        @include('partials.language-switcher')
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
                <a href="{{ route('devenir-fournisseur') }}" class="navbar-secondary-link">{{ ui('nav.become_supplier') }}</a>
                <a href="{{ route('expression-de-besoin') }}" class="btn navbar-cta">{{ ui('nav.express_need') }}</a>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-5">
                    <img src="{{ asset('img/sabonea/logo-sabonea-white.png') }}" alt="Sabonea" class="footer-logo">
                    <p class="footer-tagline">{{ ui('footer.tagline') }}</p>
                    @if ($contactEmail)
                    <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
                    @endif
                    @if ($socials)
                    <div class="footer-social">
                        @foreach ($socials as $social)
                        <a href="{{ $social['url'] }}" target="_blank" rel="noopener" aria-label="{{ $social['name'] }}"><i class="{{ $social['icon'] }}" aria-hidden="true"></i></a>
                        @endforeach
                    </div>
                    @endif
                </div>
                <div class="col-6 col-lg-3 offset-lg-1">
                    <h2 class="footer-title">{{ ui('footer.navigation') }}</h2>
                    <ul class="footer-links">
                        <li><a href="{{ route('a-propos') }}">{{ ui('nav.about') }}</a></li>
                        <li><a href="{{ route('comment-ca-fonctionne') }}">{{ ui('footer.how') }}</a></li>
                        <li><a href="{{ route('secteurs') }}">{{ ui('footer.sectors') }}</a></li>
                        <li><a href="{{ route('pourquoi-sabonea') }}">{{ ui('footer.why') }}</a></li>
                        <li><a href="{{ route('expression-de-besoin') }}">{{ ui('nav.express_need') }}</a></li>
                        <li><a href="{{ route('contact') }}">{{ ui('nav.contact') }}</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-3">
                    <h2 class="footer-title">{{ ui('footer.languages') }}</h2>
                    <p>{{ ui('footer.languages_text') }}</p>
                    <ul class="footer-langs">
                        @foreach ($languages as $language)
                        @if ($language->code === app()->getLocale())
                        <li class="is-current" lang="{{ $language->code }}"><img class="lang-flag" src="{{ $language->flagUrl() }}" alt="" width="20" height="15">{{ $language->name }}</li>
                        @elseif ($language->is_active)
                        <li lang="{{ $language->code }}"><img class="lang-flag" src="{{ $language->flagUrl() }}" alt="" width="20" height="15"><a href="{{ locale_url($language->code) }}" hreflang="{{ $language->code }}">{{ $language->name }}</a></li>
                        @else
                        <li class="is-upcoming" lang="{{ $language->code }}"><img class="lang-flag" src="{{ $language->flagUrl() }}" alt="" width="20" height="15">{{ $language->name }}</li>
                        @endif
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <span>&copy; <a href="{{ route('accueil') }}">Sabonea</a>, {{ ui('footer.rights') }}</span>
                <span class="footer-legal-links"><a href="{{ route('mentions-legales') }}">{{ ui('footer.legal_notice') }}</a><a href="{{ route('politique-de-confidentialite') }}">{{ ui('footer.privacy') }}</a></span>
                <span class="footer-credit">{{ ui('footer.credit_before') }} <a href="https://www.linkedin.com/in/kounou-gilbert-199461257?utm_source=share_via&utm_content=profile&utm_medium=member_ios/" target="_blank">Kounou Gilbert</a></span>
            </div>
        </div>
    </footer>

    <a href="#" class="back-to-top"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}?v={{ filemtime(public_path('js/main.js')) }}"></script>
    @stack('scripts')
</body>

</html>
