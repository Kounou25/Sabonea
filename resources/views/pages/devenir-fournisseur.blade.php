@extends('layouts.app')

@php
    $intro = $page->section('intro');
    $benefits = $page->section('benefits');
    $closed = $page->section('closed');
@endphp

@include('partials.supplier-form-assets')

@section('content')
    @include('partials.page-header')

    <section class="section">
        <div class="container">
            @if ($intro)
            <div class="row mb-5">
                <div class="col-lg-8">
                    @if ($intro->t('eyebrow'))
                    <p class="label">{{ $intro->t('eyebrow') }}</p>
                    @endif
                    <h2 class="t-h2">{{ $intro->t('title') }}</h2>
                    <p class="t-lead mb-0">{{ $intro->md('body') }}</p>
                </div>
            </div>
            @endif

            @if ($benefits && $benefits->items->isNotEmpty())
            <dl class="facts-list facts-list-3 mb-5 pb-4">
                @foreach ($benefits->items as $item)
                <div class="fact">
                    <dt>{{ $item->t('title') }}</dt>
                    <dd>{{ $item->md('text') }}</dd>
                </div>
                @endforeach
            </dl>
            @endif

            @if (\App\Support\SupplierForms::contactFormIsOpen())
            <livewire:supplier-contact-form />
            @elseif ($closed)
            <div class="sf-done is-locked">
                <div class="sf-done-icon is-locked"><i class="fa fa-hourglass-half" aria-hidden="true"></i></div>
                <h2 class="sf-done-title">{{ $closed->t('title') }}</h2>
                <p class="sf-done-text">{{ $closed->md('body') }}</p>
                @if ($closed->t('cta_label'))
                <a href="{{ \App\Support\SiteLink::url($closed->cta_url) }}" class="btn btn-primary">{{ $closed->t('cta_label') }}</a>
                @endif
            </div>
            @endif
        </div>
    </section>
@endsection
