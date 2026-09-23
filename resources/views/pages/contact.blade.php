@extends('layouts.app')

@php
    use App\Models\Setting;

    $intro = $page->section('intro');
    $form = $page->section('form');
    $channels = array_values(array_filter([
        ['url' => Setting::get('contact_email') ? 'mailto:'.Setting::get('contact_email') : null, 'external' => false, 'variant' => '', 'icon' => 'fa fa-envelope', 'title' => ui('contact.email'), 'label' => Setting::get('contact_email')],
        ['url' => Setting::get('linkedin_url'), 'external' => true, 'variant' => ' alt-green', 'icon' => 'fab fa-linkedin-in', 'title' => 'LinkedIn', 'label' => Setting::get('linkedin_label')],
        ['url' => Setting::get('instagram_url'), 'external' => true, 'variant' => ' alt-orange', 'icon' => 'fab fa-instagram', 'title' => 'Instagram', 'label' => Setting::get('instagram_label')],
        ['url' => Setting::get('facebook_url'), 'external' => true, 'variant' => '', 'icon' => 'fab fa-facebook-f', 'title' => 'Facebook', 'label' => Setting::get('facebook_label')],
    ], fn (array $channel) => filled($channel['url'])));
@endphp

@section('content')
    @include('partials.page-header')

    <!-- Intro Start -->
    <div class="container-fluid py-5">
        <div class="container">
            @if ($intro)
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8 wow fadeIn" data-wow-delay="0.1s">
                    <h6 class="text-sabonea-orange text-uppercase mb-2">{{ $intro->t('eyebrow') }}</h6>
                    <h2>{{ $intro->t('title') }}</h2>
                    <p class="text-muted mt-3">{{ $intro->md('subtitle') }}</p>
                </div>
            </div>
            @endif

            <div class="row g-4 justify-content-center mb-5">
                @foreach ($channels as $channel)
                <div class="col-md-6 col-lg-3 wow fadeInUp" data-wow-delay="{{ wow_delay(0.1, 0.1, $loop->index) }}">
                    <a href="{{ $channel['url'] }}"@if ($channel['external']) target="_blank" rel="noopener"@endif class="key-fact{{ $channel['variant'] }} text-center d-block text-decoration-none">
                        <div class="btn-lg-square mx-auto mb-3"><i class="{{ $channel['icon'] }} text-white"></i></div>
                        <h6 class="text-uppercase mb-1">{{ $channel['title'] }}</h6>
                        <span class="small text-muted">{{ $channel['label'] }}</span>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Intro End -->

    <!-- Contact Form Start -->
    <div class="container-fluid bg-light py-5" id="contact-form">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 wow fadeIn" data-wow-delay="0.5s">
                    <div class="vitrine-card p-4 p-lg-5">
                        <h3 class="text-uppercase mb-4 text-center">{{ $form?->t('title') }}</h3>
                        @if (session('contact_sent'))
                        <div class="form-success p-3 mb-4" role="status">{{ ui('contact.form.success') }}</div>
                        @endif
                        <form method="POST" action="{{ route('contact.store') }}#contact-form" novalidate>
                            @csrf
                            <div class="hp-field" aria-hidden="true">
                                <label for="c-website">Website</label>
                                <input type="text" id="c-website" name="website" tabindex="-1" autocomplete="off">
                            </div>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="form-floating">
                                        <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name')]) id="c-name" name="name" value="{{ old('name') }}" placeholder="{{ ui('contact.form.name') }}" required>
                                        <label for="c-name">{{ ui('contact.form.name') }}</label>
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-floating">
                                        <input type="email" @class(['form-control', 'is-invalid' => $errors->has('email')]) id="c-email" name="email" value="{{ old('email') }}" placeholder="{{ ui('contact.form.email') }}" required>
                                        <label for="c-email">{{ ui('contact.form.email') }}</label>
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-floating">
                                        <input type="text" @class(['form-control', 'is-invalid' => $errors->has('phone')]) id="c-mobile" name="phone" value="{{ old('phone') }}" placeholder="{{ ui('contact.form.phone_placeholder') }}">
                                        <label for="c-mobile">{{ ui('contact.form.phone') }}</label>
                                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-floating">
                                        <select @class(['form-select', 'is-invalid' => $errors->has('subject_option_id')]) id="c-subject" name="subject_option_id">
                                            <option value="" @selected(blank(old('subject_option_id')))>{{ ui('form.select') }}</option>
                                            @foreach ($subjects as $subject)
                                            <option value="{{ $subject->id }}" @selected(old('subject_option_id') == $subject->id)>{{ $subject->t('label') }}</option>
                                            @endforeach
                                        </select>
                                        <label for="c-subject">{{ ui('contact.form.subject') }}</label>
                                        @error('subject_option_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea style="height: 150px" @class(['form-control', 'is-invalid' => $errors->has('message')]) id="c-message" name="message" placeholder="{{ ui('contact.form.message_placeholder') }}" required>{{ old('message') }}</textarea>
                                        <label for="c-message">{{ ui('contact.form.message') }}</label>
                                        @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-12 text-center">
                                    <button class="btn btn-primary py-3 px-5" type="submit">{{ ui('contact.form.submit') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact Form End -->
@endsection
