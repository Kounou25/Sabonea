@extends('layouts.app')

@php
    use App\Models\Setting;

    $intro = $page->section('intro');
    $form = $page->section('form');
    $channels = array_values(array_filter([
        ['url' => Setting::get('contact_email') ? 'mailto:'.Setting::get('contact_email') : null, 'external' => false, 'title' => ui('contact.email'), 'label' => Setting::get('contact_email')],
        ['url' => Setting::get('linkedin_url'), 'external' => true, 'title' => 'LinkedIn', 'label' => Setting::get('linkedin_label')],
        ['url' => Setting::get('instagram_url'), 'external' => true, 'title' => 'Instagram', 'label' => Setting::get('instagram_label')],
        ['url' => Setting::get('facebook_url'), 'external' => true, 'title' => 'Facebook', 'label' => Setting::get('facebook_label')],
    ], fn (array $channel) => filled($channel['url'])));
@endphp

@section('content')
    @include('partials.page-header')

    <section class="section" id="contact-form">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-4">
                    @if ($intro)
                    @if ($intro->t('eyebrow'))
                    <p class="label">{{ $intro->t('eyebrow') }}</p>
                    @endif
                    <h2 class="t-h3">{{ $intro->t('title') }}</h2>
                    <p class="muted">{{ $intro->md('subtitle') }}</p>
                    @endif

                    @if ($channels)
                    <dl class="contact-channels">
                        @foreach ($channels as $channel)
                        <div>
                            <dt>{{ $channel['title'] }}</dt>
                            <dd><a href="{{ $channel['url'] }}"@if ($channel['external']) target="_blank" rel="noopener"@endif>{{ $channel['label'] ?: $channel['title'] }}</a></dd>
                        </div>
                        @endforeach
                    </dl>
                    @endif
                </div>

                <div class="col-lg-7 offset-lg-1">
                    <h2 class="t-h2">{{ $form?->t('title') }}</h2>
                    @if (session('contact_sent'))
                    <div class="form-success mb-4" role="status">{{ ui('contact.form.success') }}</div>
                    @endif
                    <form method="POST" action="{{ route('contact.store') }}#contact-form" novalidate>
                        @csrf
                        <div class="hp-field" aria-hidden="true">
                            <label for="c-website">Website</label>
                            <input type="text" id="c-website" name="website" tabindex="-1" autocomplete="off">
                        </div>
                        <div class="row g-4">
                            <div class="col-sm-6">
                                <label class="form-label" for="c-name">{{ ui('contact.form.name') }}</label>
                                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name')]) id="c-name" name="name" value="{{ old('name') }}" autocomplete="name" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="c-email">{{ ui('contact.form.email') }}</label>
                                <input type="email" @class(['form-control', 'is-invalid' => $errors->has('email')]) id="c-email" name="email" value="{{ old('email') }}" autocomplete="email" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="c-mobile">{{ ui('contact.form.phone') }}</label>
                                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('phone')]) id="c-mobile" name="phone" value="{{ old('phone') }}" autocomplete="tel">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="c-subject">{{ ui('contact.form.subject') }}</label>
                                <select @class(['form-select', 'is-invalid' => $errors->has('subject_option_id')]) id="c-subject" name="subject_option_id">
                                    <option value="" @selected(blank(old('subject_option_id')))>{{ ui('form.select') }}</option>
                                    @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}" @selected(old('subject_option_id') == $subject->id)>{{ $subject->t('label') }}</option>
                                    @endforeach
                                </select>
                                @error('subject_option_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="c-message">{{ ui('contact.form.message') }}</label>
                                <textarea rows="6" @class(['form-control', 'is-invalid' => $errors->has('message')]) id="c-message" name="message" required>{{ old('message') }}</textarea>
                                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <button class="btn btn-accent" type="submit">{{ ui('contact.form.submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
