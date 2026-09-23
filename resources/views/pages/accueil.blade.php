@extends('layouts.app')

@php
    $strip = $page->section('hero_strip');
    $keyFacts = $page->section('key_facts');
    $about = $page->section('about');
    $sectorsPreview = $page->section('sectors_preview');
    $how = $page->section('how_it_works');
    $why = $page->section('why');
    $whySuppliers = $page->section('why_suppliers');
    $whyBuyers = $page->section('why_buyers');
@endphp

@section('content')
    <!-- Carousel Start -->
    @if ($slides->isNotEmpty())
    <div class="container-fluid p-0 pb-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="owl-carousel header-carousel position-relative">
            @foreach ($slides as $slide)
            <div class="owl-carousel-item position-relative hero-overlay">
                <img class="img-fluid" src="{{ \App\Support\Media::url($slide->image) }}" alt="{{ $slide->t('image_alt') }}">
                <div class="owl-carousel-inner">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-11 col-lg-8">
                                @if ($slide->t('eyebrow'))
                                <div class="hero-eyebrow animated fadeInDown">{{ $slide->t('eyebrow') }}</div>
                                @endif
                                <h1 class="display-2 text-white mb-4 animated slideInDown">{{ $slide->t('title') }}</h1>
                                <p class="fs-5 fw-medium text-white mb-4 animated slideInDown">{{ $slide->md('text') }}</p>
                                <div class="hero-cta-group animated slideInLeft">
                                    @if ($slide->t('cta_label'))
                                    <a href="{{ \App\Support\SiteLink::url($slide->cta_url) }}" class="btn navbar-cta py-3 px-4">{{ $slide->t('cta_label') }}</a>
                                    @endif
                                    @if ($slide->t('cta2_label'))
                                    <a href="{{ \App\Support\SiteLink::url($slide->cta2_url) }}" class="btn btn-outline-white py-3 px-4">{{ $slide->t('cta2_label') }}</a>
                                    @endif
                                </div>
                                @if ($strip)
                                <div class="hero-sector-strip d-none d-md-flex">
                                    @foreach ($strip->items as $item)
                                    <span><i class="{{ $item->icon }}"></i>{{ $item->t('title') }}</span>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    <!-- Carousel End -->


    @if ($keyFacts)
    <!-- Key Facts Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">
                @foreach ($keyFacts->items as $item)
                <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="{{ wow_delay(0.1, 0.2, $loop->index) }}">
                    <div class="key-fact{{ $item->variant ? ' '.$item->variant : '' }}">
                        <div class="d-flex align-items-center mb-3">
                            <div class="btn-lg-square me-3">
                                <i class="{{ $item->icon }} text-white"></i>
                            </div>
                            <h3 class="mb-0 text-uppercase">{{ $item->t('title') }}</h3>
                        </div>
                        <span>{{ $item->md('text') }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Key Facts End -->
    @endif


    @if ($about)
    <!-- About Start -->
    <div class="container-fluid bg-light overflow-hidden my-5 px-lg-0">
        <div class="container about px-lg-0">
            <div class="row g-0 mx-lg-0">
                <div class="col-lg-4 ps-lg-0 wow fadeIn" data-wow-delay="0.1s" style="min-height: 400px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute img-fluid w-100 h-100" src="{{ \App\Support\Media::url($about->image) }}" style="object-fit: cover;" alt="{{ $about->t('image_alt') }}">
                    </div>
                </div>
                <div class="col-lg-8 about-text py-5 wow fadeIn" data-wow-delay="0.5s">
                    <div class="p-lg-5 pe-lg-0 position-relative">
                        <h6 class="text-sabonea-orange text-uppercase mb-2">{{ $about->t('eyebrow') }}</h6>
                        <h1 class="display-4 mb-4">{{ $about->t('title') }}</h1>
                        <p>{{ $about->md('body') }}</p>
                        @foreach ($about->items as $item)
                        <h6 class="text-uppercase"><i class="fa fa-check text-sabonea-green me-3"></i>{{ $item->t('title') }}</h6>
                        @endforeach
                        <a href="{{ \App\Support\SiteLink::url($about->cta_url) }}" class="btn btn-primary py-3 px-5 mt-3">{{ $about->t('cta_label') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->
    @endif


    @if ($sectorsPreview)
    <!-- Sectors Preview Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8">
                    <h6 class="text-sabonea-orange text-uppercase mb-2">{{ $sectorsPreview->t('eyebrow') }}</h6>
                    <h1 class="display-4 text-uppercase mb-0">{{ $sectorsPreview->t('title') }}</h1>
                </div>
            </div>
            <div class="row g-4">
                @foreach ($homeSectors as $sector)
                <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="{{ wow_delay(0.1, 0.1, $loop->index) }}">
                    <a href="{{ route('secteurs') }}" class="sector-card">
                        <img src="{{ \App\Support\Media::url($sector->image) }}" alt="{{ $sector->t('name') }}">
                        <div class="sector-overlay"><h5><i class="{{ $sector->icon }}"></i><span>{{ $sector->shortLabel() }}</span></h5></div>
                    </a>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-5">
                <a href="{{ \App\Support\SiteLink::url($sectorsPreview->cta_url) }}" class="btn btn-outline-primary py-3 px-5">{{ $sectorsPreview->t('cta_label') }}</a>
            </div>
        </div>
    </div>
    <!-- Sectors Preview End -->
    @endif


    @if ($how)
    <!-- How it works Start -->
    <div class="container-fluid bg-light overflow-hidden my-5 px-lg-0">
        <div class="container feature px-lg-0">
            <div class="row g-0 mx-lg-0">
                <div class="col-lg-8 feature-text py-5 wow fadeIn" data-wow-delay="0.1s">
                    <div class="p-lg-5 ps-lg-0">
                        <h6 class="text-sabonea-orange text-uppercase mb-2">{{ $how->t('eyebrow') }}</h6>
                        <h1 class="display-4 mb-4">{{ $how->t('title') }}</h1>
                        <p class="mb-4 pb-2">{{ $how->md('body') }}</p>
                        <div class="row g-4">
                            @foreach ($how->items as $item)
                            <div class="col-12 col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="btn-lg-square bg-primary">
                                        <i class="{{ $item->icon }} text-white"></i>
                                    </div>
                                    <div class="ms-4">
                                        <h5 class="text-uppercase mb-1">{{ $item->t('title') }}</h5>
                                        <h5 class="text-uppercase text-black-50 mb-0">{{ $item->t('text') }}</h5>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <a href="{{ \App\Support\SiteLink::url($how->cta_url) }}" class="btn btn-primary py-3 px-5 mt-4">{{ $how->t('cta_label') }}</a>
                    </div>
                </div>
                <div class="col-lg-4 pe-lg-0 wow fadeIn" data-wow-delay="0.5s" style="min-height: 400px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute img-fluid w-100 h-100" src="{{ \App\Support\Media::url($how->image) }}" style="object-fit: cover;" alt="{{ $how->t('image_alt') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- How it works End -->
    @endif


    @if ($why)
    <!-- Why Sabonea Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8">
                    <h6 class="text-sabonea-orange text-uppercase mb-2">{{ $why->t('eyebrow') }}</h6>
                    <h1 class="display-4 text-uppercase mb-0">{{ $why->t('title') }}</h1>
                </div>
            </div>
            <div class="row g-4">
                @if ($whySuppliers)
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="p-5 h-100" style="background: var(--sabonea-purple-soft); border-radius: 6px;">
                        <i class="fa fa-store text-sabonea fs-1 mb-3" style="color: var(--sabonea-purple); font-size: 40px;"></i>
                        <h3 class="text-uppercase mb-3">{{ $whySuppliers->t('title') }}</h3>
                        <p class="mb-3">{{ $whySuppliers->md('body') }}</p>
                        <ul class="list-unstyled mb-4">
                            @foreach ($whySuppliers->items as $item)
                            <li class="mb-2"><i class="fa fa-check text-sabonea-green me-2"></i>{{ $item->t('title') }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
                @if ($whyBuyers)
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="p-5 h-100" style="background: var(--sabonea-green-soft); border-radius: 6px;">
                        <i class="fa fa-search-dollar mb-3" style="color: var(--sabonea-green); font-size: 40px;"></i>
                        <h3 class="text-uppercase mb-3">{{ $whyBuyers->t('title') }}</h3>
                        <p class="mb-3">{{ $whyBuyers->md('body') }}</p>
                        <ul class="list-unstyled mb-4">
                            @foreach ($whyBuyers->items as $item)
                            <li class="mb-2"><i class="fa fa-check text-sabonea-green me-2"></i>{{ $item->t('title') }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
            </div>
            <div class="text-center mt-5">
                <a href="{{ \App\Support\SiteLink::url($why->cta_url) }}" class="btn btn-primary py-3 px-5">{{ $why->t('cta_label') }}</a>
            </div>
        </div>
    </div>
    <!-- Why Sabonea End -->
    @endif


    @include('partials.cta-banner', ['section' => $page->section('cta')])
@endsection
