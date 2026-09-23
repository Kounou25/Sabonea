@extends('layouts.app')

@section('title')
    <title>Pourquoi Sabonea   Sabonea</title>
@endsection

@section('meta')
    <meta name="description" content="Pourquoi choisir Sabonea : pour les fournisseurs, une visibilité internationale ciblée et des demandes qualifiées. Pour les acheteurs, un gain de temps et des fournisseurs vérifiés.">
@endsection

@section('content')
    <!-- Page Header Start -->
    <div class="page-header page-header-gradient d-flex align-items-center">
        <div class="container page-header-inner position-relative" style="z-index:2;">
            <h1 class="display-4 text-white mb-3">Pourquoi choisir Sabonea</h1>
            <nav class="breadcrumb bg-transparent m-0">
                <a href="{{ route('accueil') }}">Accueil</a><span class="text-white mx-2">/</span><span class="active">Pourquoi Sabonea</span>
            </nav>
        </div>
        <i class="fa fa-leaf leaf-deco"></i>
    </div>
    <!-- Page Header End -->

    <!-- For Suppliers Start -->
    <div class="container-fluid py-5">
        <div class="container about px-lg-0">
            <div class="row g-0 mx-lg-0">
                <div class="col-lg-6 ps-lg-0 wow fadeIn" data-wow-delay="0.1s" style="min-height: 420px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute img-fluid w-100 h-100" src="{{ asset('img/sabonea/pourquoi-fournisseurs.jpg') }}" style="object-fit: cover; border-radius:6px;" alt="Avantages Sabonea pour les fournisseurs">
                    </div>
                </div>
                <div class="col-lg-6 about-text py-5 wow fadeIn" data-wow-delay="0.3s">
                    <div class="p-lg-5 position-relative">
                        <h6 class="text-sabonea-orange text-uppercase mb-2"><i class="fa fa-store me-2"></i>Pour les fournisseurs</h6>
                        <h2 class="mb-4">Une vitrine à l'international, des contacts qualifiés</h2>
                        <h6 class="text-uppercase"><i class="fa fa-check text-sabonea-green me-3"></i>Une visibilité internationale ciblée, sans dispersion sur des canaux génériques</h6>
                        <h6 class="text-uppercase"><i class="fa fa-check text-sabonea-green me-3"></i>Des demandes de devis qualifiées, provenant d'acheteurs professionnels identifiés</h6>
                        <h6 class="text-uppercase"><i class="fa fa-check text-sabonea-green me-3"></i>Un accompagnement pour le développement à l'export</h6>
                        <h6 class="text-uppercase"><i class="fa fa-check text-sabonea-green me-3"></i>Une présence continue sur la plateforme, pour rester visible sur la durée</h6>
                        <a href="{{ route('contact') }}" class="btn btn-primary py-3 px-5 mt-3">Devenir fournisseur</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- For Suppliers End -->

    <!-- For Buyers Start -->
    <div class="container-fluid bg-light py-5">
        <div class="container about px-lg-0">
            <div class="row g-0 mx-lg-0 flex-lg-row-reverse">
                <div class="col-lg-6 pe-lg-0 wow fadeIn" data-wow-delay="0.1s" style="min-height: 420px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute img-fluid w-100 h-100" src="{{ asset('img/sabonea/pourquoi-acheteurs.jpg') }}" style="object-fit: cover; border-radius:6px;" alt="Avantages Sabonea pour les acheteurs">
                    </div>
                </div>
                <div class="col-lg-6 about-text py-5 wow fadeIn" data-wow-delay="0.3s">
                    <div class="p-lg-5 position-relative">
                        <h6 class="text-sabonea-green text-uppercase mb-2"><i class="fa fa-search-dollar me-2"></i>Pour les acheteurs</h6>
                        <h2 class="mb-4">Un vrai gain de temps, un interlocuteur unique</h2>
                        <h6 class="text-uppercase"><i class="fa fa-check text-sabonea-orange me-3"></i>Un seul besoin exprimé, une équipe qui identifie le bon fournisseur pour vous</h6>
                        <h6 class="text-uppercase"><i class="fa fa-check text-sabonea-orange me-3"></i>Des fournisseurs présélectionnés et vérifiés par notre équipe</h6>
                        <h6 class="text-uppercase"><i class="fa fa-check text-sabonea-orange me-3"></i>Une mise en relation qualifiée, directement avec le fournisseur le plus pertinent</h6>
                        <h6 class="text-uppercase"><i class="fa fa-check text-sabonea-orange me-3"></i>Un accompagnement dans la durée, pour tous vos besoins en équipement</h6>
                        <a href="{{ route('expression-de-besoin') }}" class="btn btn-sabonea-green py-3 px-5 mt-3">Exprimer un besoin</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- For Buyers End -->

    <!-- CTA Banner Start -->
    <div class="container-fluid bg-sabonea-purple-dark py-5 my-5">
        <div class="container py-4">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <h2 class="text-white text-uppercase mb-2">Prêt à démarrer avec Sabonea ?</h2>
                    <p class="text-white-50 mb-0">Que vous cherchiez un fournisseur ou que vous en soyez un, notre équipe vous accompagne.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('expression-de-besoin') }}" class="btn navbar-cta py-3 px-4 me-2 mb-2">Exprimer un besoin</a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-white py-3 px-4 mb-2">Nous contacter</a>
                </div>
            </div>
        </div>
    </div>
    <!-- CTA Banner End -->
@endsection
