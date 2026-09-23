@extends('layouts.app')

@php
    $ribbon = $page->section('ribbon');
    $profile = $page->section('profile');
    $tags = $page->section('tags');
    $presentation = $page->section('presentation');
    $ranges = $page->section('ranges');
    $contactCard = $page->section('contact_card');
    $checklist = $page->section('checklist');
@endphp

@section('content')
    @if ($ribbon)
    <!-- Example ribbon -->
    <div class="container-fluid bg-sabonea-orange text-center py-2">
        <small class="text-white text-uppercase fw-bold"><i class="fa fa-eye me-2"></i>{{ $ribbon->t('title') }}</small>
    </div>
    @endif

    <!-- Cover Start -->
    <div class="page-header hero-overlay" style="min-height: 260px;">
        <img class="bg-img" src="{{ \App\Support\Media::url($page->header_image) }}" alt="{{ $page->t('header_image_alt') }}">
        <div class="container page-header-inner">
            <nav class="breadcrumb bg-transparent m-0 mb-3">
                <a href="{{ route('accueil') }}">{{ ui('nav.home') }}</a><span class="text-white mx-2">/</span>
                <a href="{{ route('comment-ca-fonctionne') }}">{{ ui('footer.how') }}</a><span class="text-white mx-2">/</span>
                <span class="active">{{ $page->t('breadcrumb') }}</span>
            </nav>
        </div>
    </div>
    <!-- Cover End -->

    <!-- Supplier profile Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8 wow fadeIn" data-wow-delay="0.1s">
                    @if ($profile)
                    <div class="d-flex align-items-center mb-4">
                        <div class="btn-lg-square bg-primary text-white me-3" style="width:64px;height:64px;font-size:22px;">{{ $profile->t('eyebrow') }}</div>
                        <div>
                            <h2 class="mb-1">{{ $profile->t('title') }}</h2>
                            <span class="text-muted"><i class="fa fa-map-marker-alt me-1"></i>{{ $profile->t('subtitle') }}</span>
                        </div>
                    </div>
                    @endif

                    @if ($tags)
                    <div class="mb-4">
                        @foreach ($tags->items as $item)
                        <span class="tag-pill{{ $item->variant ? ' '.$item->variant : '' }}"><i class="{{ $item->icon }} me-1"></i>{{ $item->t('title') }}</span>
                        @endforeach
                    </div>
                    @endif

                    @if ($presentation)
                    <h5 class="text-uppercase mb-3">{{ $presentation->t('title') }}</h5>
                    <p class="text-muted">{{ $presentation->md('body') }}</p>
                    @endif

                    @if ($ranges)
                    <h5 class="text-uppercase mb-3 mt-5">{{ $ranges->t('title') }}</h5>
                    <div class="row g-4 mb-4">
                        @foreach ($ranges->items as $item)
                        <div class="col-md-4">
                            <img src="{{ \App\Support\Media::url($item->image) }}" class="img-fluid rounded mb-2" alt="{{ $item->t('title') }}">
                            <h6 class="text-uppercase mb-0">{{ $item->t('title') }}</h6>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <div class="col-lg-4 wow fadeIn" data-wow-delay="0.3s">
                    @if ($contactCard)
                    <div class="vitrine-card p-4">
                        <h5 class="text-uppercase mb-3">{{ $contactCard->t('title') }}</h5>
                        <div class="vitrine-locked mb-3">
                            <i class="fa fa-lock"></i>
                            <span>{{ $contactCard->t('subtitle') }}</span>
                        </div>
                        <p class="small text-muted mb-4">{{ $contactCard->md('body') }}</p>
                        <a href="{{ \App\Support\SiteLink::url($contactCard->cta_url) }}" class="btn navbar-cta w-100 py-3 mb-2">{{ $contactCard->t('cta_label') }}</a>
                        <a href="{{ \App\Support\SiteLink::url($contactCard->cta2_url) }}" class="btn btn-outline-primary w-100 py-3">{{ $contactCard->t('cta2_label') }}</a>
                    </div>
                    @endif

                    @if ($checklist)
                    <div class="key-fact mt-4">
                        <h6 class="text-uppercase mb-3">{{ $checklist->t('title') }}</h6>
                        @foreach ($checklist->items as $item)
                        <p class="small {{ $loop->last ? 'mb-0' : 'mb-2' }}"><i class="fa fa-check text-sabonea-green me-2"></i>{{ $item->t('title') }}</p>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Supplier profile End -->
@endsection
