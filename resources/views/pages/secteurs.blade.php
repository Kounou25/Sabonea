@extends('layouts.app')

@php
    $sectorsSection = $page->section('sectors');
    $equipmentSection = $page->section('equipment');
@endphp

@section('content')
    @include('partials.page-header')

    @if ($sectorsSection)
    <section class="section">
        <div class="container">
            <div class="section-head">
                <div>
                    @if ($sectorsSection->t('eyebrow'))
                    <p class="label">{{ $sectorsSection->t('eyebrow') }}</p>
                    @endif
                    <h2 class="t-h2">{{ $sectorsSection->t('title') }}</h2>
                </div>
            </div>
            <div class="sector-grid">
                @foreach ($sectors as $sector)
                <figure class="sector-item">
                    <img src="{{ \App\Support\Media::url($sector->image) }}" alt="">
                    <figcaption>{{ $sector->t('name') }}</figcaption>
                </figure>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if ($equipmentSection)
    <section class="section section-paper">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4">
                    @if ($equipmentSection->t('eyebrow'))
                    <p class="label">{{ $equipmentSection->t('eyebrow') }}</p>
                    @endif
                    <h2 class="t-h2">{{ $equipmentSection->t('title') }}</h2>
                </div>
                <div class="col-lg-8">
                    <ul class="index-list">
                        @foreach ($equipmentTypes as $equipmentType)
                        <li>{{ $equipmentType->t('name') }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>
    @endif

    @include('partials.cta-banner', ['section' => $page->section('cta')])
@endsection
