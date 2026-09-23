@extends('layouts.app')

@section('title')
    <title>À propos   Sabonea</title>
@endsection

@section('meta')
    <meta name="description" content="Sabonea est une marketplace B2B internationale qui connecte fournisseurs et acheteurs d'équipements professionnels de nettoyage, d'entretien et de maintenance. Découvrez notre histoire et notre mission.">
@endsection

@section('content')
    <!-- Page Header Start -->
    <div class="page-header hero-overlay">
        <img class="bg-img" src="{{ asset('img/sabonea/about-ingenieure.jpg') }}" alt="Notre histoire chez Sabonea">
        <div class="container page-header-inner">
            <h1 class="display-4 text-white mb-3">À propos de nous</h1>
            <nav class="breadcrumb bg-transparent m-0">
                <a href="{{ route('accueil') }}">Accueil</a><span class="text-white mx-2">/</span><span class="active">À propos</span>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Intro Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9 text-center wow fadeIn" data-wow-delay="0.1s">
                    <h6 class="text-sabonea-orange text-uppercase mb-2">Qui sommes-nous</h6>
                    <p class="fs-4">Sabonea est une marketplace B2B internationale qui met en relation les fournisseurs d'équipements professionnels de nettoyage, d'entretien et de maintenance avec les organisations qui en ont besoin.</p>
                    <p class="text-muted">Nous accompagnons notamment les secteurs aéroportuaire, hospitalier, municipal et industriel, en facilitant l'accès à des solutions adaptées à chaque besoin. Notre plateforme crée un lien direct entre acheteurs et fournisseurs, partout dans le monde.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Intro End -->

    <!-- Notre histoire Start -->
    <div class="container-fluid bg-light overflow-hidden my-5 px-lg-0">
        <div class="container about px-lg-0">
            <div class="row g-0 mx-lg-0">
                <div class="col-lg-5 ps-lg-0 wow fadeIn" data-wow-delay="0.1s" style="min-height: 420px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute img-fluid w-100 h-100" src="{{ asset('img/sabonea/about-mission.jpg') }}" style="object-fit: cover;" alt="Notre histoire, une conviction portée depuis le début">
                    </div>
                </div>
                <div class="col-lg-7 about-text py-5 wow fadeIn" data-wow-delay="0.5s">
                    <div class="p-lg-5 pe-lg-0 position-relative">
                        <h6 class="text-sabonea-orange text-uppercase mb-2">Notre histoire</h6>
                        <h2 class="mb-4">Une idée simple, portée par une vision plus grande</h2>
                        <p class="fs-5 fst-italic" style="color:#5c4f78;">« Le projet a commencé par une idée simple, portée par une vision plus grande : contribuer, progressivement, à des villes et des infrastructures plus propres, plus modernes et mieux équipées. »</p>
                        <p>En tant que jeune ingénieure, je construis Sabonea avec cette conviction : une entreprise peut être à la fois ambitieuse, internationale et porteuse d'un impact positif.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Notre histoire End -->

    <!-- Notre mission Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center wow fadeIn" data-wow-delay="0.1s">
                    <h6 class="text-sabonea-orange text-uppercase mb-2">Notre mission</h6>
                    <h1 class="display-5 mb-4">Donner à chaque acheteur un accès direct aux meilleurs fournisseurs</h1>
                    <p class="fs-5">Où qu'ils se trouvent dans le monde   et offrir aux fournisseurs une vitrine internationale pour développer leurs marchés à l'export.</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="key-fact text-center">
                        <div class="btn-lg-square mx-auto mb-3"><i class="fa fa-search text-white"></i></div>
                        <h6 class="text-uppercase">Simplifier la recherche</h6>
                        <span class="small">De fournisseurs fiables pour les grandes infrastructures.</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="key-fact alt-green text-center">
                        <div class="btn-lg-square mx-auto mb-3"><i class="fa fa-bullseye text-white"></i></div>
                        <h6 class="text-uppercase">Orienter chaque besoin</h6>
                        <span class="small">Vers le fournisseur le plus adapté, plutôt que de laisser l'acheteur chercher seul.</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="key-fact alt-orange text-center">
                        <div class="btn-lg-square mx-auto mb-3"><i class="fa fa-globe text-white"></i></div>
                        <h6 class="text-uppercase">Accompagner à l'export</h6>
                        <span class="small">Les fournisseurs dans leur développement à l'international.</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="0.4s">
                    <div class="key-fact text-center">
                        <div class="btn-lg-square mx-auto mb-3"><i class="fa fa-language text-white"></i></div>
                        <h6 class="text-uppercase">Rester accessible</h6>
                        <span class="small">Une plateforme multilingue, pensée pour un public professionnel mondial.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Notre mission End -->

    <!-- CTA Banner Start -->
    <div class="container-fluid bg-sabonea-purple-dark py-5 my-5">
        <div class="container py-4">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <h2 class="text-white text-uppercase mb-2">Découvrez comment nous travaillons</h2>
                    <p class="text-white-50 mb-0">De l'expression du besoin jusqu'à la mise en relation qualifiée.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('comment-ca-fonctionne') }}" class="btn navbar-cta py-3 px-5">Comment ça marche</a>
                </div>
            </div>
        </div>
    </div>
    <!-- CTA Banner End -->
@endsection
