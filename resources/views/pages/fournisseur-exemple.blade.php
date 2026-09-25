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
    <div class="specimen-ribbon">
        <div class="container">{{ $ribbon->t('title') }}</div>
    </div>
    @endif

    <header @class(['page-head', 'has-media' => $page->header_image])>
        <div class="container pb-0">
            <nav class="page-crumbs mb-4">
                <a href="{{ route('accueil') }}">{{ ui('nav.home') }}</a><span class="page-crumbs-sep" aria-hidden="true">/</span>
                <a href="{{ route('comment-ca-fonctionne') }}">{{ ui('footer.how') }}</a><span class="page-crumbs-sep" aria-hidden="true">/</span>
                <span aria-current="page">{{ $page->t('breadcrumb') }}</span>
            </nav>
        </div>
        @if ($page->header_image)
        <div class="page-head-media page-head-media-short">
            <img src="{{ \App\Support\Media::url($page->header_image) }}" alt="{{ $page->t('header_image_alt') }}">
        </div>
        @endif
    </header>

    <section class="section">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-8">
                    @if ($profile)
                    <div class="specimen-id">
                        <div class="specimen-logo">{{ $profile->t('eyebrow') }}</div>
                        <div>
                            <h1 class="t-h2 mb-1">{{ $profile->t('title') }}</h1>
                            <p class="muted mb-0">{{ $profile->t('subtitle') }}</p>
                        </div>
                    </div>
                    @endif

                    @if ($tags && $tags->items->isNotEmpty())
                    <ul class="tag-list">
                        @foreach ($tags->items as $item)
                        <li @class(['tag', 'tag-cert' => $item->variant === 'green'])>{{ $item->t('title') }}</li>
                        @endforeach
                    </ul>
                    @endif

                    @if ($presentation)
                    <h2 class="t-h3">{{ $presentation->t('title') }}</h2>
                    <p class="measure">{{ $presentation->md('body') }}</p>
                    @endif

                    @if ($ranges)
                    <h2 class="t-h3 mt-5 mb-3">{{ $ranges->t('title') }}</h2>
                    <div class="row g-4">
                        @foreach ($ranges->items as $item)
                        <div class="col-md-4">
                            <figure class="range-item">
                                <img src="{{ \App\Support\Media::url($item->image) }}" alt="">
                                <figcaption>{{ $item->t('title') }}</figcaption>
                            </figure>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <div class="col-lg-4">
                    @if ($contactCard)
                    <aside class="side-panel">
                        <h2 class="t-h3">{{ $contactCard->t('title') }}</h2>
                        <p class="side-panel-note"><i class="fa fa-lock" aria-hidden="true"></i><span>{{ $contactCard->t('subtitle') }}</span></p>
                        <p class="small muted mb-4">{{ $contactCard->md('body') }}</p>
                        <a href="{{ \App\Support\SiteLink::url($contactCard->cta_url) }}" class="btn btn-accent w-100 mb-3">{{ $contactCard->t('cta_label') }}</a>
                        <a href="{{ \App\Support\SiteLink::url($contactCard->cta2_url) }}" class="link-more">{{ $contactCard->t('cta2_label') }}</a>
                    </aside>
                    @endif

                    @if ($checklist)
                    <div class="mt-5">
                        <h2 class="label">{{ $checklist->t('title') }}</h2>
                        <ul class="rule-list rule-list-sm">
                            @foreach ($checklist->items as $item)
                            <li>{{ $item->t('title') }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
