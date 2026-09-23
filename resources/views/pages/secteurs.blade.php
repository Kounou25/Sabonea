@extends('layouts.app')

@php
    $sectorsSection = $page->section('sectors');
    $equipmentSection = $page->section('equipment');
@endphp

@section('content')
    @include('partials.page-header')

    @if ($sectorsSection)
    <!-- Sectors Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center wow fadeIn" data-wow-delay="0.1s">
                    <h6 class="text-sabonea-orange text-uppercase mb-2">{{ $sectorsSection->t('eyebrow') }}</h6>
                    <h2 class="mb-0">{{ $sectorsSection->t('title') }}</h2>
                </div>
            </div>
            <div class="row g-4">
                @foreach ($sectors as $sector)
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="{{ wow_delay(0.1, 0.05, $loop->index % 6) }}">
                    <div class="sector-card">
                        <img src="{{ \App\Support\Media::url($sector->image) }}" alt="{{ $sector->t('name') }}">
                        <div class="sector-overlay"><h5><i class="{{ $sector->icon }}"></i><span>{{ $sector->t('name') }}</span></h5></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Sectors End -->
    @endif

    @if ($equipmentSection)
    <!-- Equipment Types Start -->
    <div class="container-fluid bg-light py-5 my-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center wow fadeIn" data-wow-delay="0.1s">
                    <h6 class="text-sabonea-orange text-uppercase mb-2">{{ $equipmentSection->t('eyebrow') }}</h6>
                    <h2 class="mb-0">{{ $equipmentSection->t('title') }}</h2>
                </div>
            </div>
            <div class="row g-4">
                @foreach ($equipmentTypes as $equipmentType)
                <div class="col-sm-6 col-lg-3 wow fadeInUp" data-wow-delay="{{ wow_delay(0.1, 0.05, $loop->index % 4) }}">
                    <div class="equip-card">
                        <div class="equip-icon"><i class="{{ $equipmentType->icon }}"></i></div>
                        <h6>{{ $equipmentType->t('name') }}</h6>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Equipment Types End -->
    @endif

    @include('partials.cta-banner', ['section' => $page->section('cta')])
@endsection
