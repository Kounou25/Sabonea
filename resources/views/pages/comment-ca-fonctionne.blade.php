@extends('layouts.app')

@section('title')
    <title>Comment ça marche   Sabonea</title>
@endsection

@section('meta')
    <meta name="description" content="Découvrez le parcours fournisseur et le parcours acheteur sur Sabonea : de l'expression du besoin à la mise en relation qualifiée.">
@endsection

@section('content')
    <!-- Page Header Start -->
    <div class="page-header hero-overlay">
        <img class="bg-img" src="{{ asset('img/sabonea/comment-mise-en-relation.jpg') }}" alt="Mise en relation entre acheteur et fournisseur">
        <div class="container page-header-inner">
            <h1 class="display-4 text-white mb-3">Comment ça marche</h1>
            <nav class="breadcrumb bg-transparent m-0">
                <a href="{{ route('accueil') }}">Accueil</a><span class="text-white mx-2">/</span><span class="active">Comment ça marche</span>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Principle Callout Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="info-callout wow fadeIn" data-wow-delay="0.1s">
                        <h5 class="text-uppercase mb-2"><i class="fa fa-info-circle text-sabonea-orange me-2"></i>Sabonea n'est pas un catalogue en libre recherche</h5>
                        <p class="mb-0">Vous ne parcourez pas librement une liste de fournisseurs. Vous exprimez votre besoin via un formulaire dédié, et c'est notre équipe qui analyse la demande et vous met en relation avec le ou les fournisseurs les plus adaptés. Objectif : vous faire gagner du temps, et garantir des mises en relation réellement qualifiées.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Principle Callout End -->

    <!-- Buyers Process Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                    <h6 class="text-sabonea-green text-uppercase mb-2"><i class="fa fa-search-dollar me-2"></i>Pour les acheteurs</h6>
                    <h2 class="mb-4">Du besoin à la mise en relation</h2>

                    <div class="step-item step-green">
                        <div class="step-number">1</div>
                        <h5>Expression du besoin</h5>
                        <p class="mb-0">L'acheteur remplit un formulaire simple décrivant son besoin (type d'équipement, secteur, pays, délai).</p>
                    </div>
                    <div class="step-item step-green">
                        <div class="step-number">2</div>
                        <h5>Analyse par l'équipe Sabonea</h5>
                        <p class="mb-0">Notre équipe étudie la demande et identifie, dans son réseau, le ou les fournisseurs les plus adaptés.</p>
                    </div>
                    <div class="step-item step-green">
                        <div class="step-number">3</div>
                        <h5>Mise en relation qualifiée</h5>
                        <p class="mb-0">Sabonea transmet la demande au(x) fournisseur(s) sélectionné(s) et fait les présentations.</p>
                    </div>
                    <div class="step-item step-green">
                        <div class="step-number">4</div>
                        <h5>Échange direct</h5>
                        <p class="mb-0">L'acheteur et le fournisseur poursuivent l'échange commercial (devis, négociation, livraison) directement entre eux.</p>
                    </div>
                    <div class="step-item step-green mb-0">
                        <div class="step-number">5</div>
                        <h5>Suivi dans la durée</h5>
                        <p class="mb-0">Sabonea reste votre interlocuteur unique pour l'ensemble de vos besoins en équipements : à chaque nouvelle recherche, c'est nous qui identifions le fournisseur adapté.</p>
                    </div>

                    <a href="{{ route('expression-de-besoin') }}" class="btn btn-sabonea-green py-3 px-5 mt-3">Exprimer un besoin</a>
                </div>

                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.3s">
                    <h6 class="text-sabonea-orange text-uppercase mb-2"><i class="fa fa-store me-2"></i>Pour les fournisseurs</h6>
                    <h2 class="mb-4">De l'inscription à la demande de devis</h2>

                    <div class="step-item">
                        <div class="step-number">1</div>
                        <h5>Inscription</h5>
                        <p class="mb-0">Le fournisseur soumet son profil et ses gammes de produits via notre formulaire dédié.</p>
                    </div>
                    <div class="step-item">
                        <div class="step-number">2</div>
                        <h5>Validation</h5>
                        <p class="mb-0">L'équipe Sabonea étudie le dossier (certifications, références, conditions commerciales) et valide l'intégration.</p>
                    </div>
                    <div class="step-item">
                        <div class="step-number">3</div>
                        <h5>Référencement</h5>
                        <p class="mb-0">Le profil et les produits sont publiés sur la marketplace sous forme de vitrine (gammes, certifications, secteurs couverts), visible par les acheteurs professionnels.</p>
                    </div>
                    <div class="step-item mb-0">
                        <div class="step-number">4</div>
                        <h5>Mise en relation</h5>
                        <p class="mb-0">Le fournisseur reçoit des demandes de devis directement via la plateforme.</p>
                    </div>

                    <a href="{{ route('contact') }}" class="btn btn-primary py-3 px-5 mt-3">Devenir fournisseur</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Buyers Process End -->

    <!-- Vitrine explainer Start -->
    <div class="container-fluid bg-light overflow-hidden my-5 px-lg-0">
        <div class="container about px-lg-0">
            <div class="row g-0 mx-lg-0">
                <div class="col-lg-7 about-text py-5 wow fadeIn" data-wow-delay="0.1s">
                    <div class="p-lg-5 pe-lg-0 position-relative">
                        <h6 class="text-sabonea-orange text-uppercase mb-2">La vitrine fournisseur</h6>
                        <h2 class="mb-4">Une page publique, sans coordonnées directes</h2>
                        <p>Chaque fournisseur abonné dispose d'une page vitrine publique : logo, gammes de produits, certifications, secteurs couverts. Pour garder Sabonea au centre de chaque mise en relation, aucune coordonnée directe (e-mail, téléphone) n'y est affichée.</p>
                        <p>À la place, un bouton <strong>« Être mis en relation via Sabonea »</strong> renvoie vers le formulaire de demande, afin que chaque contact passe par une mise en relation qualifiée par notre équipe.</p>
                        <a href="{{ route('fournisseur-exemple') }}" class="btn btn-primary py-3 px-5 mt-3">Voir un exemple de vitrine</a>
                    </div>
                </div>
                <div class="col-lg-5 ps-lg-0 wow fadeIn" data-wow-delay="0.5s" style="min-height: 380px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute img-fluid w-100 h-100" src="{{ asset('img/sabonea/comment-fournisseur.jpg') }}" style="object-fit: cover;" alt="Vitrine fournisseur sur Sabonea">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Vitrine explainer End -->

    <!-- CTA Banner Start -->
    <div class="container-fluid bg-sabonea-purple-dark py-5 my-5">
        <div class="container py-4">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <h2 class="text-white text-uppercase mb-2">Une question sur le parcours ?</h2>
                    <p class="text-white-50 mb-0">Que vous soyez acheteur ou fournisseur, notre équipe vous accompagne à chaque étape.</p>
                </div>
                <div class="col-lg-5 text-lg-end">
                    <a href="{{ route('expression-de-besoin') }}" class="btn navbar-cta py-3 px-4 me-2 mb-2">Exprimer un besoin</a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-white py-3 px-4 mb-2">Nous contacter</a>
                </div>
            </div>
        </div>
    </div>
    <!-- CTA Banner End -->
@endsection
