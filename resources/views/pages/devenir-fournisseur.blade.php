@extends('layouts.app')

@php
    $intro = $page->section('intro');
    $benefits = $page->section('benefits');
    $closed = $page->section('closed');
@endphp

@include('partials.supplier-form-assets')

@section('content')
    @include('partials.page-header')

    <!-- Supplier Form Start -->
    <div class="container-fluid py-5 sf-page">
        <div class="container">
            @if ($intro)
            <div class="row justify-content-center">
                <div class="col-lg-9 text-center mb-5">
                    <h6 class="text-sabonea-orange text-uppercase mb-2">{{ $intro->t('eyebrow') }}</h6>
                    <h2 class="mb-3">{{ $intro->t('title') }}</h2>
                    <p class="mb-0">{{ $intro->md('body') }}</p>
                </div>
            </div>
            @endif

            @if ($benefits && $benefits->items->isNotEmpty())
            <div class="row g-4 mb-5">
                @foreach ($benefits->items as $item)
                <div class="col-md-4">
                    <div class="sf-benefit">
                        <span class="sf-benefit-icon"><i class="{{ $item->icon }}" aria-hidden="true"></i></span>
                        <div>
                            <strong>{{ $item->t('title') }}</strong>
                            <span>{{ $item->md('text') }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            @if (\App\Support\SupplierForms::contactFormIsOpen())
            <livewire:supplier-contact-form />
            @elseif ($closed)
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="sf-done">
                        <div class="sf-done-icon is-locked"><i class="fa fa-hourglass-half" aria-hidden="true"></i></div>
                        <h2 class="sf-done-title">{{ $closed->t('title') }}</h2>
                        <p class="sf-done-text">{{ $closed->md('body') }}</p>
                        @if ($closed->t('cta_label'))
                        <a href="{{ \App\Support\SiteLink::url($closed->cta_url) }}" class="btn btn-primary py-3 px-5">{{ $closed->t('cta_label') }}</a>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    <!-- Supplier Form End -->
@endsection
