@php
    // An unknown address matches no route: the language is not set yet, the page sets it (links need it).
    $locale = \App\Support\Locales::detect(request());
    app()->setLocale($locale);
    \Illuminate\Support\Facades\URL::defaults(['locale' => $locale]);

    $metaTitle = ui('error.404.meta_title');
    $metaDescription = ui('error.404.text');
    $noindex = true;
    $inBackOffice = request()->is('admin', 'admin/*');

    $shortcuts = [
        ['route' => 'devenir-fournisseur', 'label' => ui('nav.become_supplier'), 'icon' => 'fa-industry'],
        ['route' => 'expression-de-besoin', 'label' => ui('nav.express_need'), 'icon' => 'fa-clipboard-list'],
        ['route' => 'secteurs', 'label' => ui('footer.sectors'), 'icon' => 'fa-th-large'],
        ['route' => 'comment-ca-fonctionne', 'label' => ui('nav.how'), 'icon' => 'fa-route'],
    ];
@endphp

@extends('layouts.app')

@section('content')
    <section class="error-page">
        <div class="container">
            <div class="row align-items-center gy-5">
                <div class="col-lg-7 order-2 order-lg-1">
                    <p class="error-eyebrow">{{ ui('error.404.eyebrow') }}</p>
                    <h1 class="t-page error-title">{{ ui('error.404.title') }}</h1>
                    <p class="error-text">{{ ui('error.404.text') }}</p>

                    <div class="error-actions">
                        @if ($inBackOffice)
                        <a href="{{ url('/admin') }}" class="btn btn-primary py-3 px-5">{{ ui('error.404.back_office') }}</a>
                        @endif
                        <a href="{{ route('accueil') }}" @class(['btn py-3 px-5', 'btn-primary' => ! $inBackOffice, 'btn-outline-primary' => $inBackOffice])>{{ ui('error.404.home') }}</a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-primary py-3 px-5">{{ ui('error.404.contact') }}</a>
                    </div>
                </div>

                <div class="col-lg-5 order-1 order-lg-2">
                    <div class="error-code" aria-hidden="true">4<span class="error-code-zero">0</span>4</div>
                </div>
            </div>

            <div class="error-shortcuts">
                <h2 class="error-shortcuts-title">{{ ui('error.404.shortcuts') }}</h2>
                <div class="row g-3">
                    @foreach ($shortcuts as $shortcut)
                    <div class="col-sm-6 col-lg-3">
                        <a href="{{ route($shortcut['route']) }}" class="error-shortcut">
                            <i class="fa {{ $shortcut['icon'] }} error-shortcut-icon" aria-hidden="true"></i>
                            <span>{{ $shortcut['label'] }}</span>
                            <i class="fa fa-arrow-right error-shortcut-arrow" aria-hidden="true"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
