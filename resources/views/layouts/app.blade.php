<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    @yield('title')
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    @yield('meta')

    <!-- Favicon -->
    <link href="{{ asset('img/favicon.ico') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

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
                <div class="h-100 d-inline-flex align-items-center me-4">
                    <small class="fa fa-envelope text-sabonea-orange me-2"></small>
                    <small><a href="mailto:contact@sabonea.com">contact@sabonea.com</a></small>
                </div>
            </div>
            <div class="col-lg-5 px-5 text-end">
                <div class="h-100 d-inline-flex align-items-center me-4">
                    <small>Suivez-nous</small>
                </div>
                <div class="h-100 d-inline-flex align-items-center mx-n2">
                    <a class="btn btn-square btn-link border-0 border-end border-secondary" target="_blank" rel="noopener" href="https://www.linkedin.com/company/sabonea"><i class="fab fa-linkedin-in"></i></a>
                    <a class="btn btn-square btn-link border-0 border-end border-secondary" target="_blank" rel="noopener" href="https://www.instagram.com/sabonea_group"><i class="fab fa-instagram"></i></a>
                    <a class="btn btn-square btn-link" target="_blank" rel="noopener" href="https://www.facebook.com/profile.php?id=61594208143644"><i class="fab fa-facebook-f"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-xl bg-white navbar-light p-0">
        <a href="{{ route('accueil') }}" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <img src="{{ asset('img/sabonea/logo-sabonea.png') }}" alt="Sabonea - Direct access to the best suppliers">
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav mx-auto p-4 p-xl-0">
                <a href="{{ route('accueil') }}" class="nav-item nav-link{{ request()->routeIs('accueil') ? ' active' : '' }}">Accueil</a>
                <a href="{{ route('a-propos') }}" class="nav-item nav-link{{ request()->routeIs('a-propos') ? ' active' : '' }}">À propos</a>
                <a href="{{ route('comment-ca-fonctionne') }}" class="nav-item nav-link{{ request()->routeIs('comment-ca-fonctionne', 'fournisseur-exemple') ? ' active' : '' }}">Fonctionnement</a>
                <a href="{{ route('secteurs') }}" class="nav-item nav-link{{ request()->routeIs('secteurs') ? ' active' : '' }}">Secteurs</a>
                <a href="{{ route('pourquoi-sabonea') }}" class="nav-item nav-link{{ request()->routeIs('pourquoi-sabonea') ? ' active' : '' }}">Pourquoi nous</a>
                <a href="{{ route('contact') }}" class="nav-item nav-link{{ request()->routeIs('contact') ? ' active' : '' }}">Contact</a>
            </div>
            <div class="navbar-phone px-xl-3 d-flex align-items-center">
                <a href="{{ route('contact') }}" class="navbar-secondary-link">Devenir fournisseur</a>
                <a href="{{ route('expression-de-besoin') }}" class="btn navbar-cta py-2 px-3">Exprimer un besoin</a>
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
                    <p class="mb-3">Marketplace B2B internationale pour les équipements professionnels de nettoyage, d'entretien et de maintenance.</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i><a href="mailto:contact@sabonea.com" class="text-white-50">contact@sabonea.com</a></p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-square btn-primary me-2" target="_blank" rel="noopener" href="https://www.linkedin.com/company/sabonea"><i class="fab fa-linkedin-in"></i></a>
                        <a class="btn btn-square btn-primary me-2" target="_blank" rel="noopener" href="https://www.instagram.com/sabonea_group"><i class="fab fa-instagram"></i></a>
                        <a class="btn btn-square btn-primary me-2" target="_blank" rel="noopener" href="https://www.facebook.com/profile.php?id=61594208143644"><i class="fab fa-facebook-f"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h3 class="text-white text-uppercase mb-4">Navigation</h3>
                    <a class="btn btn-link" href="{{ route('a-propos') }}">À propos</a>
                    <a class="btn btn-link" href="{{ route('comment-ca-fonctionne') }}">Comment ça marche</a>
                    <a class="btn btn-link" href="{{ route('secteurs') }}">Secteurs &amp; équipements</a>
                    <a class="btn btn-link" href="{{ route('pourquoi-sabonea') }}">Pourquoi Sabonea</a>
                    <a class="btn btn-link" href="{{ route('expression-de-besoin') }}">Exprimer un besoin</a>
                    <a class="btn btn-link" href="{{ route('contact') }}">Contact</a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h3 class="text-white text-uppercase mb-4">Langues</h3>
                    <p class="mb-3">Le site est disponible en français. D'autres langues sont prévues prochainement :</p>
                    <div>
                        <span class="lang-pill active">Français</span>
                        <span class="lang-pill">English</span>
                        <span class="lang-pill">中文</span>
                        <span class="lang-pill">Deutsch</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright py-4" style="border-top: 1px solid rgba(255,255,255,.1);">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a href="{{ route('accueil') }}" class="text-white">Sabonea</a>, tous droits réservés.
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        Designed by <a href="https://htmlcodex.com">HTML Codex</a>, adapté pour Sabonea
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
