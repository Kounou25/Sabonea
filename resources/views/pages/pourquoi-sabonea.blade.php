@extends('layouts.app')

@php
    $suppliers = $page->section('suppliers');
    $buyers = $page->section('buyers');
@endphp

@section('content')
    @include('partials.page-header')

    @if ($suppliers)
    <!-- For Suppliers Start -->
    <div class="container-fluid py-5">
        <div class="container about px-lg-0">
            <div class="row g-0 mx-lg-0">
                <div class="col-lg-6 ps-lg-0 wow fadeIn" data-wow-delay="0.1s" style="min-height: 420px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute img-fluid w-100 h-100" src="{{ \App\Support\Media::url($suppliers->image) }}" style="object-fit: cover; border-radius:6px;" alt="{{ $suppliers->t('image_alt') }}">
                    </div>
                </div>
                <div class="col-lg-6 about-text py-5 wow fadeIn" data-wow-delay="0.3s">
                    <div class="p-lg-5 position-relative">
                        <h6 class="text-sabonea-orange text-uppercase mb-2"><i class="fa fa-store me-2"></i>{{ $suppliers->t('eyebrow') }}</h6>
                        <h2 class="mb-4">{{ $suppliers->t('title') }}</h2>
                        @foreach ($suppliers->items as $item)
                        <h6 class="text-uppercase"><i class="fa fa-check text-sabonea-green me-3"></i>{{ $item->t('title') }}</h6>
                        @endforeach
                        <a href="{{ \App\Support\SiteLink::url($suppliers->cta_url) }}" class="btn btn-primary py-3 px-5 mt-3">{{ $suppliers->t('cta_label') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- For Suppliers End -->
    @endif

    @if ($buyers)
    <!-- For Buyers Start -->
    <div class="container-fluid bg-light py-5">
        <div class="container about px-lg-0">
            <div class="row g-0 mx-lg-0 flex-lg-row-reverse">
                <div class="col-lg-6 pe-lg-0 wow fadeIn" data-wow-delay="0.1s" style="min-height: 420px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute img-fluid w-100 h-100" src="{{ \App\Support\Media::url($buyers->image) }}" style="object-fit: cover; border-radius:6px;" alt="{{ $buyers->t('image_alt') }}">
                    </div>
                </div>
                <div class="col-lg-6 about-text py-5 wow fadeIn" data-wow-delay="0.3s">
                    <div class="p-lg-5 position-relative">
                        <h6 class="text-sabonea-green text-uppercase mb-2"><i class="fa fa-search-dollar me-2"></i>{{ $buyers->t('eyebrow') }}</h6>
                        <h2 class="mb-4">{{ $buyers->t('title') }}</h2>
                        @foreach ($buyers->items as $item)
                        <h6 class="text-uppercase"><i class="fa fa-check text-sabonea-orange me-3"></i>{{ $item->t('title') }}</h6>
                        @endforeach
                        <a href="{{ \App\Support\SiteLink::url($buyers->cta_url) }}" class="btn btn-sabonea-green py-3 px-5 mt-3">{{ $buyers->t('cta_label') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- For Buyers End -->
    @endif

    @include('partials.cta-banner', ['section' => $page->section('cta')])
@endsection
