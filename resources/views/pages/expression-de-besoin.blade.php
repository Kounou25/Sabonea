@extends('layouts.app')

@php
    $intro = $page->section('intro');
@endphp

@section('content')
    @include('partials.page-header')

    <!-- Form Section Start -->
    <div class="container-fluid py-5" id="need-form">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-5 wow fadeIn" data-wow-delay="0.1s">
                    @if ($intro)
                    <h6 class="text-sabonea-orange text-uppercase mb-2">{{ $intro->t('eyebrow') }}</h6>
                    <h2 class="mb-4">{{ $intro->t('title') }}</h2>
                    <p class="mb-4">{{ $intro->md('body') }}</p>

                    @foreach ($intro->items as $item)
                    <div class="step-item step-green{{ $loop->last ? ' mb-0' : '' }}">
                        <div class="step-number">{{ $loop->iteration }}</div>
                        <h5>{{ $item->t('title') }}</h5>
                        <p class="mb-0">{{ $item->md('text') }}</p>
                    </div>
                    @endforeach

                    @if ($intro->t('note'))
                    <div class="info-callout mt-4">
                        <p class="mb-0 small"><i class="fa fa-shield-alt text-sabonea-orange me-2"></i>{{ $intro->md('note') }}</p>
                    </div>
                    @endif
                    @endif
                </div>

                <div class="col-lg-7 wow fadeIn" data-wow-delay="0.3s">
                    <div class="vitrine-card p-4 p-lg-5">
                        @if (session('need_sent'))
                        <div class="form-success p-3 mb-4" role="status">{{ ui('need.form.success') }}</div>
                        @endif
                        <form method="POST" action="{{ route('expression-de-besoin.store') }}#need-form" novalidate>
                            @csrf
                            <div class="hp-field" aria-hidden="true">
                                <label for="website">Website</label>
                                <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                            </div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <h5 class="text-uppercase mb-3">{{ ui('need.form.contact_heading') }}</h5>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-floating">
                                        <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name')]) id="nom" name="name" value="{{ old('name') }}" placeholder="{{ ui('need.form.name') }}" required>
                                        <label for="nom">{{ ui('need.form.name') }}</label>
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-floating">
                                        <input type="text" @class(['form-control', 'is-invalid' => $errors->has('company')]) id="societe" name="company" value="{{ old('company') }}" placeholder="{{ ui('need.form.company') }}">
                                        <label for="societe">{{ ui('need.form.company') }}</label>
                                        @error('company')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-floating">
                                        <input type="email" @class(['form-control', 'is-invalid' => $errors->has('email')]) id="email" name="email" value="{{ old('email') }}" placeholder="{{ ui('need.form.email') }}" required>
                                        <label for="email">{{ ui('need.form.email') }}</label>
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-floating">
                                        <input type="tel" @class(['form-control', 'is-invalid' => $errors->has('phone')]) id="telephone" name="phone" value="{{ old('phone') }}" placeholder="{{ ui('need.form.phone') }}">
                                        <label for="telephone">{{ ui('need.form.phone') }}</label>
                                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-12 mt-4">
                                    <h5 class="text-uppercase mb-3">{{ ui('need.form.need_heading') }}</h5>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-floating">
                                        <select @class(['form-select', 'is-invalid' => $errors->has('sector_id')]) id="secteur" name="sector_id">
                                            <option value="" @selected(blank(old('sector_id')))>{{ ui('form.select') }}</option>
                                            @foreach ($sectors as $sector)
                                            <option value="{{ $sector->id }}" @selected(old('sector_id') == $sector->id)>{{ $sector->t('name') }}</option>
                                            @endforeach
                                            <option value="other" @selected(old('sector_id') === 'other')>{{ ui('form.other') }}</option>
                                        </select>
                                        <label for="secteur">{{ ui('need.form.sector') }}</label>
                                        @error('sector_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-floating">
                                        <select @class(['form-select', 'is-invalid' => $errors->has('equipment_type_id')]) id="equipement" name="equipment_type_id">
                                            <option value="" @selected(blank(old('equipment_type_id')))>{{ ui('form.select') }}</option>
                                            @foreach ($equipmentTypes as $equipmentType)
                                            <option value="{{ $equipmentType->id }}" @selected(old('equipment_type_id') == $equipmentType->id)>{{ $equipmentType->t('name') }}</option>
                                            @endforeach
                                            <option value="other" @selected(old('equipment_type_id') === 'other')>{{ ui('form.other') }}</option>
                                        </select>
                                        <label for="equipement">{{ ui('need.form.equipment') }}</label>
                                        @error('equipment_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-floating">
                                        <input type="text" @class(['form-control', 'is-invalid' => $errors->has('country')]) id="pays" name="country" value="{{ old('country') }}" placeholder="{{ ui('need.form.country') }}">
                                        <label for="pays">{{ ui('need.form.country') }}</label>
                                        @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="form-floating">
                                        <select @class(['form-select', 'is-invalid' => $errors->has('deadline_option_id')]) id="delai" name="deadline_option_id">
                                            <option value="" @selected(blank(old('deadline_option_id')))>{{ ui('form.select') }}</option>
                                            @foreach ($deadlines as $deadline)
                                            <option value="{{ $deadline->id }}" @selected(old('deadline_option_id') == $deadline->id)>{{ $deadline->t('label') }}</option>
                                            @endforeach
                                        </select>
                                        <label for="delai">{{ ui('need.form.deadline') }}</label>
                                        @error('deadline_option_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea style="height: 130px" @class(['form-control', 'is-invalid' => $errors->has('message')]) id="message" name="message" placeholder="{{ ui('need.form.message_placeholder') }}">{{ old('message') }}</textarea>
                                        <label for="message">{{ ui('need.form.message') }}</label>
                                        @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-sabonea-green py-3 px-5" type="submit">{{ ui('need.form.submit') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Form Section End -->
@endsection
