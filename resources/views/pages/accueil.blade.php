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
    @if ($slides->isNotEmpty())
    <section class="hero">
        <div class="owl-carousel header-carousel">
            @foreach ($slides as $slide)
            <div class="owl-carousel-item">
                <img src="{{ \App\Support\Media::url($slide->image) }}" alt="{{ $slide->t('image_alt') }}">
                <div class="owl-carousel-inner">
                    <div class="container">
                        <div class="hero-copy">
                            @if ($slide->t('eyebrow'))
                            <p class="hero-label">{{ $slide->t('eyebrow') }}</p>
                            @endif
                            @if ($loop->first)
                            <h1 class="t-display">{{ $slide->t('title') }}</h1>
                            @else
                            <h2 class="t-display">{{ $slide->t('title') }}</h2>
                            @endif
                            <p class="hero-text">{{ $slide->md('text') }}</p>
                            <div class="hero-actions">
                                @if ($slide->t('cta_label'))
                                <a href="{{ \App\Support\SiteLink::url($slide->cta_url) }}" class="btn btn-accent">{{ $slide->t('cta_label') }}</a>
                                @endif
                                @if ($slide->t('cta2_label'))
                                <a href="{{ \App\Support\SiteLink::url($slide->cta2_url) }}" class="link-more link-light">{{ $slide->t('cta2_label') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @if ($strip && $strip->items->isNotEmpty())
        <div class="hero-strip">
            <ul>
                @foreach ($strip->items as $item)
                <li>{{ $item->t('title') }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </section>
    @endif

    @if ($keyFacts && $keyFacts->items->isNotEmpty())
    <section class="facts">
        <div class="container">
            <dl class="facts-list">
                @foreach ($keyFacts->items as $item)
                <div class="fact">
                    <dt>{{ $item->t('title') }}</dt>
                    <dd>{{ $item->md('text') }}</dd>
                </div>
                @endforeach
            </dl>
        </div>
    </section>
    @endif

    @if ($about)
    <section class="section">
        <div class="container">
            <div class="row gy-5 align-items-center">
                <div class="col-lg-6">
                    @if ($about->t('eyebrow'))
                    <p class="label">{{ $about->t('eyebrow') }}</p>
                    @endif
                    <h2 class="t-h2">{{ $about->t('title') }}</h2>
                    <p class="t-lead">{{ $about->md('body') }}</p>
                    @if ($about->items->isNotEmpty())
                    <ul class="rule-list">
                        @foreach ($about->items as $item)
                        <li>{{ $item->t('title') }}</li>
                        @endforeach
                    </ul>
                    @endif
                    <a href="{{ \App\Support\SiteLink::url($about->cta_url) }}" class="link-more">{{ $about->t('cta_label') }}</a>
                </div>
                <div class="col-lg-5 offset-lg-1">
                    <figure class="figure-portrait">
                        <img src="{{ \App\Support\Media::url($about->image) }}" alt="{{ $about->t('image_alt') }}">
                    </figure>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if ($sectorsPreview)
    <section class="section section-paper">
        <div class="container">
            <div class="section-head">
                <h2 class="t-h2">{{ $sectorsPreview->t('title') }}</h2>
                <a href="{{ \App\Support\SiteLink::url($sectorsPreview->cta_url) }}" class="link-more">{{ $sectorsPreview->t('cta_label') }}</a>
            </div>
            <div class="sector-mosaic">
                @foreach ($homeSectors as $sector)
                <a href="{{ route('secteurs') }}" class="sector-tile">
                    <img src="{{ \App\Support\Media::url($sector->image) }}" alt="">
                    <span class="sector-tile-name">{{ $sector->shortLabel() }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if ($how)
    <section>
        @if ($how->image)
        <div class="how-media">
            <img src="{{ \App\Support\Media::url($how->image) }}" alt="{{ $how->t('image_alt') }}">
        </div>
        @endif
        <div class="section section-ink">
            <div class="container">
                <div class="row gy-5">
                    <div class="col-lg-5">
                        @if ($how->t('eyebrow'))
                        <p class="label">{{ $how->t('eyebrow') }}</p>
                        @endif
                        <h2 class="t-h2">{{ $how->t('title') }}</h2>
                        <p class="mb-4">{{ $how->md('body') }}</p>
                        <a href="{{ \App\Support\SiteLink::url($how->cta_url) }}" class="link-more link-light">{{ $how->t('cta_label') }}</a>
                    </div>
                    <div class="col-lg-6 offset-lg-1">
                        <ol class="step-list step-list-light mb-0">
                            @foreach ($how->items as $item)
                            <li>
                                <span class="step-list-title">{{ $item->t('title') }}</span>
                                @if ($item->t('text'))
                                <span class="step-list-text">{{ $item->t('text') }}</span>
                                @endif
                            </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if ($why)
    <section class="section">
        <div class="container">
            <div class="section-head">
                <h2 class="t-h2">{{ $why->t('title') }}</h2>
                <a href="{{ \App\Support\SiteLink::url($why->cta_url) }}" class="link-more">{{ $why->t('cta_label') }}</a>
            </div>
            <div class="row gy-5">
                @if ($whyBuyers)
                <div class="col-lg-7">
                    <h3 class="t-h3">{{ $whyBuyers->t('title') }}</h3>
                    <p class="t-lead">{{ $whyBuyers->md('body') }}</p>
                    <ul class="rule-list rule-list-lg mb-0">
                        @foreach ($whyBuyers->items as $item)
                        <li>{{ $item->t('title') }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @if ($whySuppliers)
                <div class="col-lg-4 offset-lg-1 duo-side">
                    <h3 class="t-h3">{{ $whySuppliers->t('title') }}</h3>
                    <p>{{ $whySuppliers->md('body') }}</p>
                    <ul class="rule-list rule-list-sm">
                        @foreach ($whySuppliers->items as $item)
                        <li>{{ $item->t('title') }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    @include('partials.cta-banner', ['section' => $page->section('cta')])
@endsection
