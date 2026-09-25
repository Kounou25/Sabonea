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
    <section class="section">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-5">
                    <h2 class="t-h2 mb-0">{{ $principle->t('title') }}</h2>
                </div>
                <div class="col-lg-6 offset-lg-1">
                    <p class="t-lead mb-0">{{ $principle->md('body') }}</p>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if ($buyers || $suppliers)
    <section class="section section-paper">
        <div class="container">
            <div class="row gy-5">
                @if ($buyers)
                <div class="col-lg-7">
                    @if ($buyers->t('eyebrow'))
                    <p class="label">{{ $buyers->t('eyebrow') }}</p>
                    @endif
                    <h2 class="t-h2">{{ $buyers->t('title') }}</h2>
                    <ol class="step-list step-list-lg">
                        @foreach ($buyers->items as $item)
                        <li>
                            <span class="step-list-title">{{ $item->t('title') }}</span>
                            <span class="step-list-text">{{ $item->md('text') }}</span>
                        </li>
                        @endforeach
                    </ol>
                    <a href="{{ \App\Support\SiteLink::url($buyers->cta_url) }}" class="btn btn-accent">{{ $buyers->t('cta_label') }}</a>
                </div>
                @endif

                @if ($suppliers)
                <div class="col-lg-4 offset-lg-1">
                    <div class="side-panel">
                        @if ($suppliers->t('eyebrow'))
                        <p class="label">{{ $suppliers->t('eyebrow') }}</p>
                        @endif
                        <h2 class="t-h3">{{ $suppliers->t('title') }}</h2>
                        <ol class="step-list step-list-compact">
                            @foreach ($suppliers->items as $item)
                            <li>
                                <span class="step-list-title">{{ $item->t('title') }}</span>
                                <span class="step-list-text">{{ $item->md('text') }}</span>
                            </li>
                            @endforeach
                        </ol>
                        <a href="{{ \App\Support\SiteLink::url($suppliers->cta_url) }}" class="btn btn-outline-primary">{{ $suppliers->t('cta_label') }}</a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    @if ($showcase)
    <section class="section">
        <div class="container">
            <div class="row gy-5 align-items-center">
                <div class="col-lg-6">
                    @if ($showcase->t('eyebrow'))
                    <p class="label">{{ $showcase->t('eyebrow') }}</p>
                    @endif
                    <h2 class="t-h2">{{ $showcase->t('title') }}</h2>
                    <p>{{ $showcase->md('body') }}</p>
                    <p class="note">{{ $showcase->md('note') }}</p>
                    <a href="{{ \App\Support\SiteLink::url($showcase->cta_url) }}" class="link-more">{{ $showcase->t('cta_label') }}</a>
                </div>
                <div class="col-lg-5 offset-lg-1">
                    <figure class="figure-portrait">
                        <img src="{{ \App\Support\Media::url($showcase->image) }}" alt="{{ $showcase->t('image_alt') }}">
                    </figure>
                </div>
            </div>
        </div>
    </section>
    @endif

    @include('partials.cta-banner', ['section' => $page->section('cta')])
@endsection
