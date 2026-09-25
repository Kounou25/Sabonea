@extends('layouts.app')

@php
    $intro = $page->section('intro');
    $story = $page->section('story');
    $mission = $page->section('mission');
@endphp

@section('content')
    @include('partials.page-header')

    @if ($intro)
    <section class="section">
        <div class="container">
            <div class="row gy-3">
                <div class="col-lg-3">
                    <p class="label">{{ $intro->t('eyebrow') }}</p>
                </div>
                <div class="col-lg-9">
                    <p class="t-lead-xl">{{ $intro->md('subtitle') }}</p>
                    <p class="muted measure mb-0">{{ $intro->md('body') }}</p>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if ($story)
    <section class="section section-paper">
        <div class="container">
            <div class="row gy-5 align-items-center">
                <div class="col-lg-5">
                    <figure class="figure-portrait">
                        <img src="{{ \App\Support\Media::url($story->image) }}" alt="{{ $story->t('image_alt') }}">
                    </figure>
                </div>
                <div class="col-lg-6 offset-lg-1">
                    @if ($story->t('eyebrow'))
                    <p class="label">{{ $story->t('eyebrow') }}</p>
                    @endif
                    <h2 class="t-h2">{{ $story->t('title') }}</h2>
                    <blockquote class="t-quote">{{ $story->md('subtitle') }}</blockquote>
                    <p class="mb-0">{{ $story->md('body') }}</p>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if ($mission)
    <section class="section">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-5">
                    @if ($mission->t('eyebrow'))
                    <p class="label">{{ $mission->t('eyebrow') }}</p>
                    @endif
                    <h2 class="t-h2">{{ $mission->t('title') }}</h2>
                    <p class="t-lead mb-0">{{ $mission->md('subtitle') }}</p>
                </div>
                <div class="col-lg-6 offset-lg-1">
                    <ul class="entry-list">
                        @foreach ($mission->items as $item)
                        <li>
                            <span class="entry-list-title">{{ $item->t('title') }}</span>
                            <span class="entry-list-text">{{ $item->md('text') }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>
    @endif

    @include('partials.cta-banner', ['section' => $page->section('cta')])
@endsection
