@extends('layouts.app')

@php
    $intro = $page->section('intro');
@endphp

@section('content')
    @include('partials.page-header')

    <section class="section" id="need-form">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-4">
                    @if ($intro)
                    <div class="sticky-aside">
                        @if ($intro->t('eyebrow'))
                        <p class="label">{{ $intro->t('eyebrow') }}</p>
                        @endif
                        <h2 class="t-h2">{{ $intro->t('title') }}</h2>
                        <p>{{ $intro->md('body') }}</p>

                        @if ($intro->items->isNotEmpty())
                        <ol class="step-list step-list-compact mt-4 mb-4">
                            @foreach ($intro->items as $item)
                            <li>
                                <span class="step-list-title">{{ $item->t('title') }}</span>
                                <span class="step-list-text">{{ $item->md('text') }}</span>
                            </li>
                            @endforeach
                        </ol>
                        @endif

                        @if ($intro->t('note'))
                        <p class="note mb-0">{{ $intro->md('note') }}</p>
                        @endif
                    </div>
                    @endif
                </div>

                <div class="col-lg-7 offset-lg-1">
                    @if (session('need_sent'))
                    <div class="form-success mb-4" role="status">{{ ui('need.form.success') }}</div>
                    @endif
                    <form method="POST" action="{{ route('expression-de-besoin.store') }}#need-form" novalidate>
                        @csrf
                        <div class="hp-field" aria-hidden="true">
                            <label for="website">Website</label>
                            <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                        </div>

                        <fieldset>
                            <legend class="form-legend">{{ ui('need.form.contact_heading') }}</legend>
                            <div class="row g-4">
                                <div class="col-sm-6">
                                    <label class="form-label" for="nom">{{ ui('need.form.name') }}</label>
                                    <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name')]) id="nom" name="name" value="{{ old('name') }}" autocomplete="name" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" for="societe">{{ ui('need.form.company') }}</label>
                                    <input type="text" @class(['form-control', 'is-invalid' => $errors->has('company')]) id="societe" name="company" value="{{ old('company') }}" autocomplete="organization">
                                    @error('company')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" for="email">{{ ui('need.form.email') }}</label>
                                    <input type="email" @class(['form-control', 'is-invalid' => $errors->has('email')]) id="email" name="email" value="{{ old('email') }}" autocomplete="email" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" for="telephone">{{ ui('need.form.phone') }}</label>
                                    <input type="tel" @class(['form-control', 'is-invalid' => $errors->has('phone')]) id="telephone" name="phone" value="{{ old('phone') }}" autocomplete="tel">
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </fieldset>

                        <fieldset>
                            <legend class="form-legend">{{ ui('need.form.need_heading') }}</legend>
                            <div class="row g-4">
                                <div class="col-sm-6">
                                    <label class="form-label" for="secteur">{{ ui('need.form.sector') }}</label>
                                    <select @class(['form-select', 'is-invalid' => $errors->has('sector_id')]) id="secteur" name="sector_id">
                                        <option value="" @selected(blank(old('sector_id')))>{{ ui('form.select') }}</option>
                                        @foreach ($sectors as $sector)
                                        <option value="{{ $sector->id }}" @selected(old('sector_id') == $sector->id)>{{ $sector->formLabel() }}</option>
                                        @endforeach
                                    </select>
                                    @error('sector_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" for="equipement">{{ ui('need.form.equipment') }}</label>
                                    <select @class(['form-select', 'is-invalid' => $errors->has('equipment_type_id')]) id="equipement" name="equipment_type_id">
                                        <option value="" @selected(blank(old('equipment_type_id')))>{{ ui('form.select') }}</option>
                                        @foreach ($equipmentTypes as $equipmentType)
                                        <option value="{{ $equipmentType->id }}" @selected(old('equipment_type_id') == $equipmentType->id)>{{ $equipmentType->formLabel() }}</option>
                                        @endforeach
                                    </select>
                                    @error('equipment_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" for="pays">{{ ui('need.form.country') }}</label>
                                    <select @class(['form-select', 'is-invalid' => $errors->has('country')]) id="pays" name="country">
                                        <option value="" @selected(blank(old('country')))>{{ ui('form.select') }}</option>
                                        @foreach (\App\Support\Countries::options() as $code => $countryName)
                                        <option value="{{ $code }}" @selected(old('country') === $code)>{{ $countryName }}</option>
                                        @endforeach
                                    </select>
                                    @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" for="delai">{{ ui('need.form.deadline') }}</label>
                                    <select @class(['form-select', 'is-invalid' => $errors->has('deadline_option_id')]) id="delai" name="deadline_option_id">
                                        <option value="" @selected(blank(old('deadline_option_id')))>{{ ui('form.select') }}</option>
                                        @foreach ($deadlines as $deadline)
                                        <option value="{{ $deadline->id }}" @selected(old('deadline_option_id') == $deadline->id)>{{ $deadline->t('label') }}</option>
                                        @endforeach
                                    </select>
                                    @error('deadline_option_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="message">{{ ui('need.form.message') }}</label>
                                    <textarea rows="5" @class(['form-control', 'is-invalid' => $errors->has('message')]) id="message" name="message">{{ old('message') }}</textarea>
                                    @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </fieldset>

                        <button class="btn btn-accent mt-4" type="submit">{{ ui('need.form.submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
