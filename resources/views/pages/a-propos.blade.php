@extends('layouts.app')

@php
    $intro = $page->section('intro');
    $story = $page->section('story');
    $mission = $page->section('mission');
@endphp

@section('content')
    @include('partials.page-header')

    @if ($intro)
    <!-- Intro Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9 text-center wow fadeIn" data-wow-delay="0.1s">
                    <h6 class="text-sabonea-orange text-uppercase mb-2">{{ $intro->t('eyebrow') }}</h6>
                    <p class="fs-4">{{ $intro->md('subtitle') }}</p>
                    <p class="text-muted">{{ $intro->md('body') }}</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Intro End -->
    @endif

    @if ($story)
    <!-- Notre histoire Start -->
    <div class="container-fluid bg-light overflow-hidden my-5 px-lg-0">
        <div class="container about px-lg-0">
            <div class="row g-0 mx-lg-0">
                <div class="col-lg-5 ps-lg-0 wow fadeIn" data-wow-delay="0.1s" style="min-height: 420px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute img-fluid w-100 h-100" src="{{ \App\Support\Media::url($story->image) }}" style="object-fit: cover;" alt="{{ $story->t('image_alt') }}">
                    </div>
                </div>
                <div class="col-lg-7 about-text py-5 wow fadeIn" data-wow-delay="0.5s">
                    <div class="p-lg-5 pe-lg-0 position-relative">
                        <h6 class="text-sabonea-orange text-uppercase mb-2">{{ $story->t('eyebrow') }}</h6>
                        <h2 class="mb-4">{{ $story->t('title') }}</h2>
                        <p class="fs-5 fst-italic" style="color:#5c4f78;">{{ $story->md('subtitle') }}</p>
                        <p>{{ $story->md('body') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Notre histoire End -->
    @endif

    @if ($mission)
    <!-- Notre mission Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center wow fadeIn" data-wow-delay="0.1s">
                    <h6 class="text-sabonea-orange text-uppercase mb-2">{{ $mission->t('eyebrow') }}</h6>
                    <h1 class="display-5 mb-4">{{ $mission->t('title') }}</h1>
                    <p class="fs-5">{{ $mission->md('subtitle') }}</p>
                </div>
            </div>
            <div class="row g-4">
                @foreach ($mission->items as $item)
                <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="{{ wow_delay(0.1, 0.1, $loop->index) }}">
                    <div class="key-fact{{ $item->variant ? ' '.$item->variant : '' }} text-center">
                        <div class="btn-lg-square mx-auto mb-3"><i class="{{ $item->icon }} text-white"></i></div>
                        <h6 class="text-uppercase">{{ $item->t('title') }}</h6>
                        <span class="small">{{ $item->md('text') }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Notre mission End -->
    @endif

    @include('partials.cta-banner', ['section' => $page->section('cta')])
@endsection
