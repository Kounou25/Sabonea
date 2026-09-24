@extends('layouts.app')

@php
    $content = $page->section('content');
@endphp

@section('content')
    @include('partials.page-header')

    <!-- Legal Content Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9 legal-content">
                    {!! $content?->t('body') !!}
                </div>
            </div>
        </div>
    </div>
    <!-- Legal Content End -->
@endsection
