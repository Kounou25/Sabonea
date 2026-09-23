@extends('layouts.app')

@section('title')
    <title>Contact   Sabonea</title>
@endsection

@section('meta')
    <meta name="description" content="Une question, une demande de partenariat, ou vous souhaitez rejoindre Sabonea en tant que fournisseur ? Contactez-nous par e-mail ou sur les réseaux sociaux.">
@endsection

@section('content')
    <!-- Page Header Start -->
    <div class="page-header hero-overlay">
        <img class="bg-img" src="{{ asset('img/sabonea/contact-banner.jpg') }}" alt="Contacter Sabonea">
        <div class="container page-header-inner">
            <h1 class="display-4 text-white mb-3">Contact</h1>
            <nav class="breadcrumb bg-transparent m-0">
                <a href="{{ route('accueil') }}">Accueil</a><span class="text-white mx-2">/</span><span class="active">Contact</span>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Intro Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8 wow fadeIn" data-wow-delay="0.1s">
                    <h6 class="text-sabonea-orange text-uppercase mb-2">Parlons-en</h6>
                    <h2>Une question, une demande de partenariat, ou vous souhaitez rejoindre Sabonea en tant que fournisseur ?</h2>
                    <p class="text-muted mt-3">Contactez-nous   nous revenons vers vous rapidement.</p>
                </div>
            </div>

            <div class="row g-4 justify-content-center mb-5">
                <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="0.1s">
                    <a href="mailto:contact@sabonea.com" class="key-fact text-center d-block text-decoration-none">
                        <div class="btn-lg-square mx-auto mb-3"><i class="fa fa-envelope text-white"></i></div>
                        <h6 class="text-uppercase mb-1">E-mail</h6>
                        <span class="small text-muted">contact@sabonea.com</span>
                    </a>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="0.2s">
                    <a href="https://www.linkedin.com/company/sabonea" target="_blank" rel="noopener" class="key-fact alt-green text-center d-block text-decoration-none">
                        <div class="btn-lg-square mx-auto mb-3"><i class="fab fa-linkedin-in text-white"></i></div>
                        <h6 class="text-uppercase mb-1">LinkedIn</h6>
                        <span class="small text-muted">@sabonea</span>
                    </a>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="0.3s">
                    <a href="https://www.instagram.com/sabonea_group" target="_blank" rel="noopener" class="key-fact alt-orange text-center d-block text-decoration-none">
                        <div class="btn-lg-square mx-auto mb-3"><i class="fab fa-instagram text-white"></i></div>
                        <h6 class="text-uppercase mb-1">Instagram</h6>
                        <span class="small text-muted">@sabonea_group</span>
                    </a>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="0.4s">
                    <a href="https://www.facebook.com/profile.php?id=61594208143644" target="_blank" rel="noopener" class="key-fact text-center d-block text-decoration-none">
                        <div class="btn-lg-square mx-auto mb-3"><i class="fab fa-facebook-f text-white"></i></div>
                        <h6 class="text-uppercase mb-1">Facebook</h6>
                        <span class="small text-muted">Sabonea</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Intro End -->

    <!-- Contact Form Start -->
    <div class="container-fluid bg-light py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 wow fadeIn" data-wow-delay="0.5s">
                    <div class="vitrine-card p-4 p-lg-5">
                        <h3 class="text-uppercase mb-4 text-center">Envoyez-nous un message</h3>
                        <form>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="c-name" placeholder="Votre nom">
                                        <label for="c-name">Votre nom</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="c-email" placeholder="Votre e-mail">
                                        <label for="c-email">Votre e-mail</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="c-mobile" placeholder="Téléphone">
                                        <label for="c-mobile">Téléphone (optionnel)</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="c-subject">
                                            <option selected>Sélectionner...</option>
                                            <option>Je suis acheteur   j'ai un besoin en équipement</option>
                                            <option>Je suis fournisseur   je souhaite rejoindre Sabonea</option>
                                            <option>Demande de partenariat</option>
                                            <option>Autre question</option>
                                        </select>
                                        <label for="c-subject">Objet de votre message</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea style="height: 150px" class="form-control" id="c-message" placeholder="Message"></textarea>
                                        <label for="c-message">Votre message</label>
                                    </div>
                                </div>
                                <div class="col-12 text-center">
                                    <button class="btn btn-primary py-3 px-5" type="submit">Envoyer le message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact Form End -->
@endsection
