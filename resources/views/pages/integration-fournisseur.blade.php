@extends('layouts.app')

@include('partials.supplier-form-assets')

@section('content')
    @include('partials.page-header')

    <section class="section">
        <div class="container">
            <livewire:supplier-onboarding-form :token="request()->route('token')" />
        </div>
    </section>
@endsection
