@extends('layouts.app')

@php
    $content = $page->section('content');
@endphp

@section('content')
    @include('partials.page-header')

    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 legal-content">
                    {!! $content?->t('body') !!}
                </div>
            </div>
        </div>
    </section>
@endsection
