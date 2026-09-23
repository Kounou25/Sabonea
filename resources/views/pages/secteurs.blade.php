@extends('layouts.app')

@section('title')
    <title>Secteurs & équipements   Sabonea</title>
@endsection

@section('meta')
    <meta name="description" content="Sabonea couvre les équipements professionnels pour les aéroports, hôpitaux, municipalités, sites industriels, chantiers, hôtels, centres commerciaux, universités et plateformes logistiques.">
@endsection

@section('content')
    <!-- Page Header Start -->
    <div class="page-header page-header-gradient d-flex align-items-center">
        <div class="container page-header-inner position-relative" style="z-index:2;">
            <h1 class="display-4 text-white mb-3">Secteurs &amp; équipements</h1>
            <nav class="breadcrumb bg-transparent m-0">
                <a href="{{ route('accueil') }}">Accueil</a><span class="text-white mx-2">/</span><span class="active">Secteurs</span>
            </nav>
        </div>
        <i class="fa fa-leaf leaf-deco"></i>
    </div>
    <!-- Page Header End -->

    <!-- Sectors Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center wow fadeIn" data-wow-delay="0.1s">
                    <h6 class="text-sabonea-orange text-uppercase mb-2">Nos secteurs d'activité</h6>
                    <h2 class="mb-0">Sabonea couvre les équipements professionnels destinés à :</h2>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="sector-card">
                        <img src="{{ asset('img/sabonea/secteur-aeroports.jpg') }}" alt="Aéroports">
                        <div class="sector-overlay"><h5><i class="fa fa-plane"></i><span>Aéroports</span></h5></div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.15s">
                    <div class="sector-card">
                        <img src="{{ asset('img/sabonea/secteur-hopitaux.jpg') }}" alt="Hôpitaux">
                        <div class="sector-overlay"><h5><i class="fa fa-hospital"></i><span>Hôpitaux</span></h5></div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="sector-card">
                        <img src="{{ asset('img/sabonea/secteur-chantiers.jpg') }}" alt="Chantiers de construction">
                        <div class="sector-overlay"><h5><i class="fa fa-hard-hat"></i><span>Chantiers de construction</span></h5></div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.25s">
                    <div class="sector-card">
                        <img src="{{ asset('img/sabonea/secteur-hotels.jpg') }}" alt="Hôtels">
                        <div class="sector-overlay"><h5><i class="fa fa-hotel"></i><span>Hôtels</span></h5></div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="sector-card">
                        <img src="{{ asset('img/sabonea/secteur-logistique.jpg') }}" alt="Plateformes logistiques">
                        <div class="sector-overlay"><h5><i class="fa fa-warehouse"></i><span>Plateformes logistiques</span></h5></div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.35s">
                    <div class="sector-card">
                        <img src="{{ asset('img/sabonea/secteur-municipalites.jpg') }}" alt="Municipalités et villes">
                        <div class="sector-overlay"><h5><i class="fa fa-city"></i><span>Municipalités / villes</span></h5></div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="sector-card">
                        <img src="{{ asset('img/sabonea/secteur-industriel.jpg') }}" alt="Sites industriels">
                        <div class="sector-overlay"><h5><i class="fa fa-industry"></i><span>Sites industriels</span></h5></div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.15s">
                    <div class="sector-card">
                        <img src="{{ asset('img/sabonea/secteur-commerces.jpg') }}" alt="Centres commerciaux">
                        <div class="sector-overlay"><h5><i class="fa fa-shopping-bag"></i><span>Centres commerciaux</span></h5></div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="sector-card">
                        <img src="{{ asset('img/sabonea/secteur-universites.jpg') }}" alt="Universités et campus">
                        <div class="sector-overlay"><h5><i class="fa fa-university"></i><span>Universités / campus</span></h5></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Sectors End -->

    <!-- Equipment Types Start -->
    <div class="container-fluid bg-light py-5 my-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center wow fadeIn" data-wow-delay="0.1s">
                    <h6 class="text-sabonea-orange text-uppercase mb-2">Types d'équipements</h6>
                    <h2 class="mb-0">Les familles de machines que nous référençons</h2>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-sm-6 col-lg-3 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="equip-card">
                        <div class="equip-icon"><i class="fa fa-broom"></i></div>
                        <h6>Balayeuses / nettoyeuses de voirie</h6>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 wow fadeInUp" data-wow-delay="0.15s">
                    <div class="equip-card">
                        <div class="equip-icon"><i class="fa fa-tint"></i></div>
                        <h6>Machines de nettoyage haute pression</h6>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="equip-card">
                        <div class="equip-icon"><i class="fa fa-plane-departure"></i></div>
                        <h6>Équipements de nettoyage aéroportuaire</h6>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 wow fadeInUp" data-wow-delay="0.25s">
                    <div class="equip-card">
                        <div class="equip-icon"><i class="fa fa-snowplow"></i></div>
                        <h6>Équipements de déneigement</h6>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="equip-card">
                        <div class="equip-icon"><i class="fa fa-industry"></i></div>
                        <h6>Machines de nettoyage industriel</h6>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 wow fadeInUp" data-wow-delay="0.15s">
                    <div class="equip-card">
                        <div class="equip-icon"><i class="fa fa-building"></i></div>
                        <h6>Nettoyage de façades / bâtiments</h6>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="equip-card">
                        <div class="equip-icon"><i class="fa fa-road"></i></div>
                        <h6>Entretien des routes et voiries</h6>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 wow fadeInUp" data-wow-delay="0.25s">
                    <div class="equip-card">
                        <div class="equip-icon"><i class="fa fa-dumpster"></i></div>
                        <h6>Véhicules de collecte des déchets</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Equipment Types End -->

    <!-- CTA Banner Start -->
    <div class="container-fluid bg-sabonea-purple-dark py-5 my-5">
        <div class="container py-4">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <h2 class="text-white text-uppercase mb-2">Vous ne trouvez pas votre équipement ?</h2>
                    <p class="text-white-50 mb-0">Décrivez-nous votre besoin : notre équipe identifie le fournisseur adapté dans son réseau.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('expression-de-besoin') }}" class="btn navbar-cta py-3 px-5">Exprimer un besoin</a>
                </div>
            </div>
        </div>
    </div>
    <!-- CTA Banner End -->
@endsection
