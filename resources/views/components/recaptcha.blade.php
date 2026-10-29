{{-- "I'm not a robot" box of the contact and buyer forms (App\Support\Recaptcha), once its keys are configured. --}}
@if (\App\Support\Recaptcha::enabled())
<div {{ $attributes->class('recaptcha') }}>
    <div class="g-recaptcha" data-sitekey="{{ \App\Support\Recaptcha::siteKey() }}"></div>
    @error(\App\Support\Recaptcha::FIELD)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>

@pushOnce('scripts')
<script src="{{ \App\Support\Recaptcha::scriptUrl() }}" async defer></script>
@endPushOnce
@endif
