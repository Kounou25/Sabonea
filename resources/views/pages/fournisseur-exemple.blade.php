@extends('layouts.app')

@section('title')
    <title>Exemple de vitrine fournisseur   Sabonea</title>
@endsection

@section('meta')
    <meta name="description" content="Exemple illustratif d'une page vitrine fournisseur sur Sabonea : gammes, certifications, secteurs couverts   sans coordonnées de contact directes.">
    <meta name="robots" content="noindex">
@endsection

@section('content')
    <!-- Example ribbon -->
    <div class="container-fluid bg-sabonea-orange text-center py-2">
        <small class="text-white text-uppercase fw-bold"><i class="fa fa-eye me-2"></i>Exemple illustratif de vitrine fournisseur   contenu fictif à titre de maquette</small>
    </div>

    <!-- Cover Start -->
    <div class="page-header hero-overlay" style="min-height: 260px;">
        <img class="bg-img" src="{{ asset('img/sabonea/fournisseur-cover.jpg') }}" alt="Bannière de la vitrine fournisseur">
        <div class="container page-header-inner">
            <nav class="breadcrumb bg-transparent m-0 mb-3">
                <a href="{{ route('accueil') }}">Accueil</a><span class="text-white mx-2">/</span>
                <a href="{{ route('comment-ca-fonctionne') }}">Comment ça marche</a><span class="text-white mx-2">/</span>
                <span class="active">Exemple de vitrine</span>
            </nav>
        </div>
    </div>
    <!-- Cover End -->

    <!-- Supplier profile Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8 wow fadeIn" data-wow-delay="0.1s">
                    <div class="d-flex align-items-center mb-4">
                        <div class="btn-lg-square bg-primary text-white me-3" style="width:64px;height:64px;font-size:22px;">NF</div>
                        <div>
                            <h2 class="mb-1">Nom du fournisseur</h2>
                            <span class="text-muted"><i class="fa fa-map-marker-alt me-1"></i>Pays d'origine   Fournisseur abonné</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <span class="tag-pill"><i class="fa fa-plane me-1"></i>Aéroports</span>
                        <span class="tag-pill"><i class="fa fa-city me-1"></i>Municipalités</span>
                        <span class="tag-pill"><i class="fa fa-industry me-1"></i>Sites industriels</span>
                        <span class="tag-pill green"><i class="fa fa-certificate me-1"></i>ISO 9001</span>
                        <span class="tag-pill green"><i class="fa fa-certificate me-1"></i>Marquage CE</span>
                    </div>

                    <h5 class="text-uppercase mb-3">Présentation</h5>
                    <p class="text-muted">C'est ici que s'affiche la présentation du fournisseur : son activité, son expérience, ses marchés couverts et ce qui le distingue. Ce contenu est fourni par le fournisseur lors de son inscription puis validé par l'équipe Sabonea avant publication.</p>

                    <h5 class="text-uppercase mb-3 mt-5">Gammes de produits</h5>
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <img src="{{ asset('img/sabonea/equip-balayeuse.jpg') }}" class="img-fluid rounded mb-2" alt="Gamme balayeuses de voirie">
                            <h6 class="text-uppercase mb-0">Balayeuses de voirie</h6>
                        </div>
                        <div class="col-md-4">
                            <img src="{{ asset('img/sabonea/equip-haute-pression.jpg') }}" class="img-fluid rounded mb-2" alt="Gamme nettoyeurs haute pression">
                            <h6 class="text-uppercase mb-0">Nettoyeurs haute pression</h6>
                        </div>
                        <div class="col-md-4">
                            <img src="{{ asset('img/sabonea/equip-scrubber.jpg') }}" class="img-fluid rounded mb-2" alt="Gamme autolaveuses industrielles">
                            <h6 class="text-uppercase mb-0">Autolaveuses industrielles</h6>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 wow fadeIn" data-wow-delay="0.3s">
                    <div class="vitrine-card p-4">
                        <h5 class="text-uppercase mb-3">Mise en relation</h5>
                        <div class="vitrine-locked mb-3">
                            <i class="fa fa-lock"></i>
                            <span>Coordonnées non affichées   mise en relation gérée par Sabonea</span>
                        </div>
                        <p class="small text-muted mb-4">Pour préserver la qualité des échanges, aucun e-mail ni numéro de téléphone n'est publié sur cette page. Toute demande passe par notre équipe.</p>
                        <a href="{{ route('expression-de-besoin') }}" class="btn navbar-cta w-100 py-3 mb-2">Être mis en relation via Sabonea</a>
                        <a href="{{ route('secteurs') }}" class="btn btn-outline-primary w-100 py-3">Voir tous les secteurs</a>
                    </div>

                    <div class="key-fact mt-4">
                        <h6 class="text-uppercase mb-3">Sur cette vitrine</h6>
                        <p class="small mb-2"><i class="fa fa-check text-sabonea-green me-2"></i>Logo &amp; présentation</p>
                        <p class="small mb-2"><i class="fa fa-check text-sabonea-green me-2"></i>Gammes de produits</p>
                        <p class="small mb-2"><i class="fa fa-check text-sabonea-green me-2"></i>Certifications</p>
                        <p class="small mb-0"><i class="fa fa-check text-sabonea-green me-2"></i>Secteurs couverts</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Supplier profile End -->
@endsection
