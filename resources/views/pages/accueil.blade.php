@extends('layouts.app')

@section('title')
    <title>Sabonea   Direct access to the best suppliers</title>
@endsection

@section('meta')
    <meta name="keywords" content="Sabonea, marketplace B2B, fournisseurs équipements nettoyage, entretien, maintenance, aéroportuaire, hospitalier, municipal, industriel">
    <meta name="description" content="Sabonea est une marketplace B2B internationale qui met en relation les fournisseurs d'équipements professionnels de nettoyage, d'entretien et de maintenance avec les organisations qui en ont besoin.">
@endsection

@section('content')
    <!-- Carousel Start -->
    <div class="container-fluid p-0 pb-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="owl-carousel header-carousel position-relative">
            <div class="owl-carousel-item position-relative hero-overlay">
                <img class="img-fluid" src="{{ asset('img/sabonea/hero-aeroport.jpg') }}" alt="Équipements professionnels sur tarmac d'aéroport">
                <div class="owl-carousel-inner">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-11 col-lg-8">
                                <div class="hero-eyebrow animated fadeInDown">Marketplace B2B internationale</div>
                                <h1 class="display-2 text-white mb-4 animated slideInDown">Direct access to the best suppliers</h1>
                                <p class="fs-5 fw-medium text-white mb-4 animated slideInDown">Sabonea met en relation les fournisseurs d'équipements professionnels de nettoyage, d'entretien et de maintenance avec les organisations qui en ont besoin, partout dans le monde.</p>
                                <div class="hero-cta-group animated slideInLeft">
                                    <a href="{{ route('expression-de-besoin') }}" class="btn navbar-cta py-3 px-4">Exprimer un besoin</a>
                                    <a href="{{ route('contact') }}" class="btn btn-outline-white py-3 px-4">Devenir fournisseur</a>
                                </div>
                                <div class="hero-sector-strip d-none d-md-flex">
                                    <span><i class="fa fa-plane"></i>Aéroports</span>
                                    <span><i class="fa fa-hospital"></i>Hôpitaux</span>
                                    <span><i class="fa fa-city"></i>Municipalités</span>
                                    <span><i class="fa fa-industry"></i>Industriel</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="owl-carousel-item position-relative hero-overlay">
                <img class="img-fluid" src="{{ asset('img/sabonea/hero-voirie-nuit.jpg') }}" alt="Nettoyage municipal et entretien de la voirie">
                <div class="owl-carousel-inner">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-11 col-lg-8">
                                <div class="hero-eyebrow animated fadeInDown">Marketplace B2B internationale</div>
                                <h1 class="display-2 text-white mb-4 animated slideInDown">Un accès direct aux meilleurs fournisseurs</h1>
                                <p class="fs-5 fw-medium text-white mb-4 animated slideInDown">Aéroports, hôpitaux, municipalités, sites industriels : nous analysons votre besoin et vous orientons vers le fournisseur le plus adapté.</p>
                                <div class="hero-cta-group animated slideInLeft">
                                    <a href="{{ route('expression-de-besoin') }}" class="btn navbar-cta py-3 px-4">Exprimer un besoin</a>
                                    <a href="{{ route('comment-ca-fonctionne') }}" class="btn btn-outline-white py-3 px-4">Comment ça marche</a>
                                </div>
                                <div class="hero-sector-strip d-none d-md-flex">
                                    <span><i class="fa fa-plane"></i>Aéroports</span>
                                    <span><i class="fa fa-hospital"></i>Hôpitaux</span>
                                    <span><i class="fa fa-city"></i>Municipalités</span>
                                    <span><i class="fa fa-industry"></i>Industriel</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="owl-carousel-item position-relative hero-overlay">
                <img class="img-fluid" src="{{ asset('img/sabonea/hero-logistique.jpg') }}" alt="Logistique et équipements industriels">
                <div class="owl-carousel-inner">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-11 col-lg-8">
                                <div class="hero-eyebrow animated fadeInDown">Marketplace B2B internationale</div>
                                <h1 class="display-2 text-white mb-4 animated slideInDown">Une vitrine internationale pour nos fournisseurs</h1>
                                <p class="fs-5 fw-medium text-white mb-4 animated slideInDown">Nous accompagnons les fournisseurs dans leur développement à l'export, avec des demandes qualifiées provenant d'acheteurs professionnels identifiés.</p>
                                <div class="hero-cta-group animated slideInLeft">
                                    <a href="{{ route('contact') }}" class="btn navbar-cta py-3 px-4">Devenir fournisseur</a>
                                    <a href="{{ route('pourquoi-sabonea') }}" class="btn btn-outline-white py-3 px-4">Pourquoi Sabonea</a>
                                </div>
                                <div class="hero-sector-strip d-none d-md-flex">
                                    <span><i class="fa fa-plane"></i>Aéroports</span>
                                    <span><i class="fa fa-hospital"></i>Hôpitaux</span>
                                    <span><i class="fa fa-city"></i>Municipalités</span>
                                    <span><i class="fa fa-industry"></i>Industriel</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Carousel End -->


    <!-- Key Facts Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="key-fact">
                        <div class="d-flex align-items-center mb-3">
                            <div class="btn-lg-square me-3">
                                <i class="fa fa-globe-americas text-white"></i>
                            </div>
                            <h3 class="mb-0 text-uppercase">Internationale</h3>
                        </div>
                        <span>Une marketplace B2B pensée pour connecter acheteurs et fournisseurs partout dans le monde.</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="key-fact alt-green">
                        <div class="d-flex align-items-center mb-3">
                            <div class="btn-lg-square me-3">
                                <i class="fa fa-layer-group text-white"></i>
                            </div>
                            <h3 class="mb-0 text-uppercase">4 secteurs</h3>
                        </div>
                        <span>Aéroportuaire, hospitalier, municipal et industriel : des besoins que nous connaissons.</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="key-fact alt-orange">
                        <div class="d-flex align-items-center mb-3">
                            <div class="btn-lg-square me-3">
                                <i class="fa fa-user-check text-white"></i>
                            </div>
                            <h3 class="mb-0 text-uppercase">Mise en relation qualifiée</h3>
                        </div>
                        <span>Chaque besoin est analysé par notre équipe avant d'être transmis au bon fournisseur.</span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="0.7s">
                    <div class="key-fact">
                        <div class="d-flex align-items-center mb-3">
                            <div class="btn-lg-square me-3">
                                <i class="fa fa-language text-white"></i>
                            </div>
                            <h3 class="mb-0 text-uppercase">Multilingue</h3>
                        </div>
                        <span>Disponible en français, avec l'anglais, le chinois et l'allemand prévus prochainement.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Key Facts End -->


    <!-- About Start -->
    <div class="container-fluid bg-light overflow-hidden my-5 px-lg-0">
        <div class="container about px-lg-0">
            <div class="row g-0 mx-lg-0">
                <div class="col-lg-4 ps-lg-0 wow fadeIn" data-wow-delay="0.1s" style="min-height: 400px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute img-fluid w-100 h-100" src="{{ asset('img/sabonea/about-ingenieure.jpg') }}" style="object-fit: cover;" alt="Sabonea, une entreprise portée par une vision d'infrastructures plus propres">
                    </div>
                </div>
                <div class="col-lg-8 about-text py-5 wow fadeIn" data-wow-delay="0.5s">
                    <div class="p-lg-5 pe-lg-0 position-relative">
                        <h6 class="text-sabonea-orange text-uppercase mb-2">À propos de Sabonea</h6>
                        <h1 class="display-4 mb-4">Contribuer à des villes et des infrastructures plus propres, plus modernes</h1>
                        <p>Sabonea est une marketplace B2B internationale qui met en relation les fournisseurs d'équipements professionnels de nettoyage, d'entretien et de maintenance avec les organisations qui en ont besoin   notamment dans les secteurs aéroportuaire, hospitalier, municipal et industriel.</p>
                        <h6 class="text-uppercase"><i class="fa fa-check text-sabonea-green me-3"></i>Un lien direct entre acheteurs et fournisseurs</h6>
                        <h6 class="text-uppercase"><i class="fa fa-check text-sabonea-green me-3"></i>Une sélection analysée par notre équipe</h6>
                        <h6 class="text-uppercase"><i class="fa fa-check text-sabonea-green me-3"></i>Un accompagnement à l'international</h6>
                        <a href="{{ route('a-propos') }}" class="btn btn-primary py-3 px-5 mt-3">Découvrir notre histoire</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->


    <!-- Sectors Preview Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8">
                    <h6 class="text-sabonea-orange text-uppercase mb-2">Nos secteurs</h6>
                    <h1 class="display-4 text-uppercase mb-0">Des besoins que nous comprenons, sur le terrain</h1>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="0.1s">
                    <a href="{{ route('secteurs') }}" class="sector-card">
                        <img src="{{ asset('img/sabonea/secteur-aeroports.jpg') }}" alt="Secteur aéroportuaire">
                        <div class="sector-overlay"><h5><i class="fa fa-plane"></i><span>Aéroports</span></h5></div>
                    </a>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="0.2s">
                    <a href="{{ route('secteurs') }}" class="sector-card">
                        <img src="{{ asset('img/sabonea/secteur-hopitaux.jpg') }}" alt="Secteur hospitalier">
                        <div class="sector-overlay"><h5><i class="fa fa-hospital"></i><span>Hôpitaux</span></h5></div>
                    </a>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="0.3s">
                    <a href="{{ route('secteurs') }}" class="sector-card">
                        <img src="{{ asset('img/sabonea/secteur-municipalites.jpg') }}" alt="Secteur municipal">
                        <div class="sector-overlay"><h5><i class="fa fa-city"></i><span>Municipalités</span></h5></div>
                    </a>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="0.4s">
                    <a href="{{ route('secteurs') }}" class="sector-card">
                        <img src="{{ asset('img/sabonea/secteur-industriel.jpg') }}" alt="Sites industriels">
                        <div class="sector-overlay"><h5><i class="fa fa-industry"></i><span>Sites industriels</span></h5></div>
                    </a>
                </div>
            </div>
            <div class="text-center mt-5">
                <a href="{{ route('secteurs') }}" class="btn btn-outline-primary py-3 px-5">Voir tous les secteurs &amp; équipements</a>
            </div>
        </div>
    </div>
    <!-- Sectors Preview End -->


    <!-- How it works Start -->
    <div class="container-fluid bg-light overflow-hidden my-5 px-lg-0">
        <div class="container feature px-lg-0">
            <div class="row g-0 mx-lg-0">
                <div class="col-lg-8 feature-text py-5 wow fadeIn" data-wow-delay="0.1s">
                    <div class="p-lg-5 ps-lg-0">
                        <h6 class="text-sabonea-orange text-uppercase mb-2">Comment ça fonctionne</h6>
                        <h1 class="display-4 mb-4">Un formulaire, une équipe, la bonne mise en relation</h1>
                        <p class="mb-4 pb-2">Sabonea n'est pas un catalogue en libre recherche. Vous exprimez votre besoin, notre équipe l'analyse et identifie le ou les fournisseurs les plus adaptés dans son réseau   puis fait les présentations.</p>
                        <div class="row g-4">
                            <div class="col-12 col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="btn-lg-square bg-primary">
                                        <i class="fa fa-file-signature text-white"></i>
                                    </div>
                                    <div class="ms-4">
                                        <h5 class="text-uppercase mb-1">Expression</h5>
                                        <h5 class="text-uppercase text-black-50 mb-0">du besoin</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="btn-lg-square bg-primary">
                                        <i class="fa fa-search text-white"></i>
                                    </div>
                                    <div class="ms-4">
                                        <h5 class="text-uppercase mb-1">Analyse</h5>
                                        <h5 class="text-uppercase text-black-50 mb-0">par l'équipe</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="btn-lg-square bg-primary">
                                        <i class="fa fa-handshake text-white"></i>
                                    </div>
                                    <div class="ms-4">
                                        <h5 class="text-uppercase mb-1">Mise en relation</h5>
                                        <h5 class="text-uppercase text-black-50 mb-0">qualifiée</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="btn-lg-square bg-primary">
                                        <i class="fa fa-sync-alt text-white"></i>
                                    </div>
                                    <div class="ms-4">
                                        <h5 class="text-uppercase mb-1">Suivi</h5>
                                        <h5 class="text-uppercase text-black-50 mb-0">dans la durée</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('comment-ca-fonctionne') }}" class="btn btn-primary py-3 px-5 mt-4">Voir le parcours complet</a>
                    </div>
                </div>
                <div class="col-lg-4 pe-lg-0 wow fadeIn" data-wow-delay="0.5s" style="min-height: 400px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute img-fluid w-100 h-100" src="{{ asset('img/sabonea/comment-mise-en-relation.jpg') }}" style="object-fit: cover;" alt="Mise en relation qualifiée entre acheteur et fournisseur">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- How it works End -->


    <!-- Why Sabonea Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8">
                    <h6 class="text-sabonea-orange text-uppercase mb-2">Pourquoi Sabonea</h6>
                    <h1 class="display-4 text-uppercase mb-0">Deux publics, un même gain de temps</h1>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="p-5 h-100" style="background: var(--sabonea-purple-soft); border-radius: 6px;">
                        <i class="fa fa-store text-sabonea fs-1 mb-3" style="color: var(--sabonea-purple); font-size: 40px;"></i>
                        <h3 class="text-uppercase mb-3">Pour les fournisseurs</h3>
                        <p class="mb-3">Une visibilité internationale ciblée et des demandes de devis qualifiées, sans dispersion sur des canaux génériques.</p>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="fa fa-check text-sabonea-green me-2"></i>Demandes qualifiées</li>
                            <li class="mb-2"><i class="fa fa-check text-sabonea-green me-2"></i>Accompagnement à l'export</li>
                            <li class="mb-2"><i class="fa fa-check text-sabonea-green me-2"></i>Présence continue sur la plateforme</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="p-5 h-100" style="background: var(--sabonea-green-soft); border-radius: 6px;">
                        <i class="fa fa-search-dollar mb-3" style="color: var(--sabonea-green); font-size: 40px;"></i>
                        <h3 class="text-uppercase mb-3">Pour les acheteurs</h3>
                        <p class="mb-3">Un seul besoin exprimé, une équipe qui identifie le bon fournisseur pour vous : un vrai gain de temps.</p>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2"><i class="fa fa-check text-sabonea-green me-2"></i>Fournisseurs présélectionnés et vérifiés</li>
                            <li class="mb-2"><i class="fa fa-check text-sabonea-green me-2"></i>Mise en relation qualifiée</li>
                            <li class="mb-2"><i class="fa fa-check text-sabonea-green me-2"></i>Un interlocuteur unique dans la durée</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5">
                <a href="{{ route('pourquoi-sabonea') }}" class="btn btn-primary py-3 px-5">En savoir plus</a>
            </div>
        </div>
    </div>
    <!-- Why Sabonea End -->


    <!-- CTA Banner Start -->
    <div class="container-fluid bg-sabonea-purple-dark py-5 my-5">
        <div class="container py-4">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <h2 class="text-white text-uppercase mb-2">Prêt à trouver le bon fournisseur ?</h2>
                    <p class="text-white-50 mb-0">Décrivez votre besoin : type d'équipement, secteur, pays, délai. Notre équipe s'occupe du reste.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('expression-de-besoin') }}" class="btn navbar-cta py-3 px-5">Exprimer un besoin</a>
                </div>
            </div>
        </div>
    </div>
    <!-- CTA Banner End -->
@endsection
