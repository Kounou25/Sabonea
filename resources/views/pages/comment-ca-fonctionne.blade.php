@extends('layouts.app')

@php
    $principle = $page->section('principle');
    $buyers = $page->section('buyers');
    $suppliers = $page->section('suppliers');
    $showcase = $page->section('showcase');
@endphp

@section('content')
    @include('partials.page-header')

    @if ($principle)
    <!-- Principle Callout Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="info-callout wow fadeIn" data-wow-delay="0.1s">
                        <h5 class="text-uppercase mb-2"><i class="fa fa-info-circle text-sabonea-orange me-2"></i>{{ $principle->t('title') }}</h5>
                        <p class="mb-0">{{ $principle->md('body') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Principle Callout End -->
    @endif

    <!-- Buyers Process Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">
                @if ($buyers)
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                    <h6 class="text-sabonea-green text-uppercase mb-2"><i class="fa fa-search-dollar me-2"></i>{{ $buyers->t('eyebrow') }}</h6>
                    <h2 class="mb-4">{{ $buyers->t('title') }}</h2>

                    @foreach ($buyers->items as $item)
                    <div class="step-item step-green{{ $loop->last ? ' mb-0' : '' }}">
                        <div class="step-number">{{ $loop->iteration }}</div>
                        <h5>{{ $item->t('title') }}</h5>
                        <p class="mb-0">{{ $item->md('text') }}</p>
                    </div>
                    @endforeach

                    <a href="{{ \App\Support\SiteLink::url($buyers->cta_url) }}" class="btn btn-sabonea-green py-3 px-5 mt-3">{{ $buyers->t('cta_label') }}</a>
                </div>
                @endif

                @if ($suppliers)
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.3s">
                    <h6 class="text-sabonea-orange text-uppercase mb-2"><i class="fa fa-store me-2"></i>{{ $suppliers->t('eyebrow') }}</h6>
                    <h2 class="mb-4">{{ $suppliers->t('title') }}</h2>

                    @foreach ($suppliers->items as $item)
                    <div class="step-item{{ $loop->last ? ' mb-0' : '' }}">
                        <div class="step-number">{{ $loop->iteration }}</div>
                        <h5>{{ $item->t('title') }}</h5>
                        <p class="mb-0">{{ $item->md('text') }}</p>
                    </div>
                    @endforeach

                    <a href="{{ \App\Support\SiteLink::url($suppliers->cta_url) }}" class="btn btn-primary py-3 px-5 mt-3">{{ $suppliers->t('cta_label') }}</a>
                </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Buyers Process End -->

    @if ($showcase)
    <!-- Vitrine explainer Start -->
    <div class="container-fluid bg-light overflow-hidden my-5 px-lg-0">
        <div class="container about px-lg-0">
            <div class="row g-0 mx-lg-0">
                <div class="col-lg-7 about-text py-5 wow fadeIn" data-wow-delay="0.1s">
                    <div class="p-lg-5 pe-lg-0 position-relative">
                        <h6 class="text-sabonea-orange text-uppercase mb-2">{{ $showcase->t('eyebrow') }}</h6>
                        <h2 class="mb-4">{{ $showcase->t('title') }}</h2>
                        <p>{{ $showcase->md('body') }}</p>
                        <p>{{ $showcase->md('note') }}</p>
                        <a href="{{ \App\Support\SiteLink::url($showcase->cta_url) }}" class="btn btn-primary py-3 px-5 mt-3">{{ $showcase->t('cta_label') }}</a>
                    </div>
                </div>
                <div class="col-lg-5 ps-lg-0 wow fadeIn" data-wow-delay="0.5s" style="min-height: 380px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute img-fluid w-100 h-100" src="{{ \App\Support\Media::url($showcase->image) }}" style="object-fit: cover;" alt="{{ $showcase->t('image_alt') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Vitrine explainer End -->
    @endif

    @include('partials.cta-banner', ['section' => $page->section('cta'), 'textColumns' => 7])
@endsection
