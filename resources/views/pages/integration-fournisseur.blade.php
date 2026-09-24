@extends('layouts.app')

@include('partials.supplier-form-assets')

@section('content')
    @include('partials.page-header')

    <!-- Onboarding Form Start -->
    <div class="container-fluid py-5 sf-page">
        <div class="container">
            <livewire:supplier-onboarding-form :token="request()->route('token')" />
        </div>
    </div>
    <!-- Onboarding Form End -->
@endsection
