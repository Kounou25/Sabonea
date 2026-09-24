@php
    $contactEmail = \App\Models\Setting::get('contact_email');
@endphp
<div class="sf-side-card sf-side-help">
    <div class="sf-side-help-icon"><i class="fa fa-comments" aria-hidden="true"></i></div>
    <div>
        <strong>{{ ui('supplier_form.help_title') }}</strong>
        @if ($contactEmail)
        <p class="mb-0">{{ ui('supplier_form.help_text') }} <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a></p>
        @endif
    </div>
</div>
<p class="sf-secure-note"><i class="fa fa-shield-alt" aria-hidden="true"></i>{{ ui('supplier_form.secure_note') }}</p>
