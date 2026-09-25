@extends('layouts.app')

@php
    $suppliers = $page->section('suppliers');
    $buyers = $page->section('buyers');
@endphp

@section('content')
    @include('partials.page-header')

    @if ($buyers)
    <section class="section">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-5">
                    @if ($buyers->t('eyebrow'))
                    <p class="label">{{ $buyers->t('eyebrow') }}</p>
                    @endif
                    <h2 class="t-h2">{{ $buyers->t('title') }}</h2>
                    <a href="{{ \App\Support\SiteLink::url($buyers->cta_url) }}" class="btn btn-accent mt-2">{{ $buyers->t('cta_label') }}</a>
                </div>
                <div class="col-lg-6 offset-lg-1">
                    <ul class="rule-list rule-list-lg mt-0">
                        @foreach ($buyers->items as $item)
                        <li>{{ $item->t('title') }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @if ($buyers->image)
            <figure class="figure-wide mt-5">
                <img src="{{ \App\Support\Media::url($buyers->image) }}" alt="{{ $buyers->t('image_alt') }}">
            </figure>
            @endif
        </div>
    </section>
    @endif

    @if ($suppliers)
    <section class="section section-paper">
        <div class="container">
            <div class="row gy-5 align-items-center">
                <div class="col-lg-4">
                    <figure class="figure-portrait">
                        <img src="{{ \App\Support\Media::url($suppliers->image) }}" alt="{{ $suppliers->t('image_alt') }}">
                    </figure>
                </div>
                <div class="col-lg-7 offset-lg-1">
                    @if ($suppliers->t('eyebrow'))
                    <p class="label">{{ $suppliers->t('eyebrow') }}</p>
                    @endif
                    <h2 class="t-h2">{{ $suppliers->t('title') }}</h2>
                    <ul class="rule-list">
                        @foreach ($suppliers->items as $item)
                        <li>{{ $item->t('title') }}</li>
                        @endforeach
                    </ul>
                    <a href="{{ \App\Support\SiteLink::url($suppliers->cta_url) }}" class="btn btn-outline-primary">{{ $suppliers->t('cta_label') }}</a>
                </div>
            </div>
        </div>
    </section>
    @endif

    @include('partials.cta-banner', ['section' => $page->section('cta')])
@endsection
