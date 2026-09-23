@extends('layouts.app')

@section('title')
    <title>Exprimer un besoin   Sabonea</title>
@endsection

@section('meta')
    <meta name="description" content="Décrivez votre besoin en équipement professionnel de nettoyage, d'entretien ou de maintenance. L'équipe Sabonea analyse votre demande et vous met en relation avec le fournisseur le plus adapté.">
@endsection

@section('content')
    <!-- Page Header Start -->
    <div class="page-header page-header-gradient d-flex align-items-center">
        <div class="container page-header-inner position-relative" style="z-index:2;">
            <h1 class="display-4 text-white mb-3">Exprimer un besoin</h1>
            <nav class="breadcrumb bg-transparent m-0">
                <a href="{{ route('accueil') }}">Accueil</a><span class="text-white mx-2">/</span><span class="active">Exprimer un besoin</span>
            </nav>
        </div>
        <i class="fa fa-leaf leaf-deco"></i>
    </div>
    <!-- Page Header End -->

    <!-- Form Section Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-5 wow fadeIn" data-wow-delay="0.1s">
                    <h6 class="text-sabonea-orange text-uppercase mb-2">Étape 1 sur 3</h6>
                    <h2 class="mb-4">Décrivez votre besoin en quelques champs</h2>
                    <p class="mb-4">Type d'équipement, secteur, pays, délai souhaité : ces quelques informations suffisent à notre équipe pour commencer l'analyse de votre demande.</p>

                    <div class="step-item step-green">
                        <div class="step-number">1</div>
                        <h5>Vous exprimez votre besoin</h5>
                        <p class="mb-0">Via ce formulaire, en quelques minutes.</p>
                    </div>
                    <div class="step-item step-green">
                        <div class="step-number">2</div>
                        <h5>Notre équipe analyse la demande</h5>
                        <p class="mb-0">Et identifie le ou les fournisseurs les plus adaptés dans son réseau.</p>
                    </div>
                    <div class="step-item step-green mb-0">
                        <div class="step-number">3</div>
                        <h5>Vous êtes mis en relation</h5>
                        <p class="mb-0">Directement avec le fournisseur sélectionné, pour poursuivre l'échange (devis, négociation, livraison).</p>
                    </div>

                    <div class="info-callout mt-4">
                        <p class="mb-0 small"><i class="fa fa-shield-alt text-sabonea-orange me-2"></i>Vos coordonnées ne sont partagées qu'au moment de la mise en relation qualifiée par notre équipe   jamais publiées ni diffusées librement.</p>
                    </div>
                </div>

                <div class="col-lg-7 wow fadeIn" data-wow-delay="0.3s">
                    <div class="vitrine-card p-4 p-lg-5">
                        <form>
                            <div class="row g-3">
                                <div class="col-12">
                                    <h5 class="text-uppercase mb-3">Vos coordonnées</h5>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="nom" placeholder="Nom complet">
                                        <label for="nom">Nom complet</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="societe" placeholder="Société / Organisation">
                                        <label for="societe">Société / Organisation</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="email" placeholder="E-mail professionnel">
                                        <label for="email">E-mail professionnel</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-floating">
                                        <input type="tel" class="form-control" id="telephone" placeholder="Téléphone">
                                        <label for="telephone">Téléphone</label>
                                    </div>
                                </div>

                                <div class="col-12 mt-4">
                                    <h5 class="text-uppercase mb-3">Votre besoin</h5>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="secteur">
                                            <option selected>Sélectionner...</option>
                                            <option>Aéroports</option>
                                            <option>Hôpitaux</option>
                                            <option>Chantiers de construction</option>
                                            <option>Hôtels</option>
                                            <option>Plateformes logistiques</option>
                                            <option>Municipalités / villes</option>
                                            <option>Sites industriels</option>
                                            <option>Centres commerciaux</option>
                                            <option>Universités / campus</option>
                                            <option>Autre</option>
                                        </select>
                                        <label for="secteur">Secteur</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="equipement">
                                            <option selected>Sélectionner...</option>
                                            <option>Balayeuses / nettoyeuses de voirie</option>
                                            <option>Machines de nettoyage haute pression</option>
                                            <option>Équipements de nettoyage aéroportuaire</option>
                                            <option>Équipements de déneigement</option>
                                            <option>Machines de nettoyage industriel</option>
                                            <option>Nettoyage de façades / bâtiments</option>
                                            <option>Entretien des routes et voiries</option>
                                            <option>Véhicules de collecte des déchets</option>
                                            <option>Autre</option>
                                        </select>
                                        <label for="equipement">Type d'équipement</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="pays" placeholder="Pays">
                                        <label for="pays">Pays</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="delai">
                                            <option selected>Sélectionner...</option>
                                            <option>Urgent (moins d'1 mois)</option>
                                            <option>1 à 3 mois</option>
                                            <option>3 à 6 mois</option>
                                            <option>À définir</option>
                                        </select>
                                        <label for="delai">Délai souhaité</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea style="height: 130px" class="form-control" id="message" placeholder="Précisions sur votre besoin"></textarea>
                                        <label for="message">Précisions sur votre besoin (quantité, spécifications, contexte...)</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-sabonea-green py-3 px-5" type="submit">Envoyer ma demande</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Form Section End -->
@endsection
