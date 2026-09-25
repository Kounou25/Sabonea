@php
    $contactEmail = \App\Models\Setting::get('contact_email');
@endphp
<div class="sf-side-card sf-side-help">
    <strong>{{ ui('supplier_form.help_title') }}</strong>
    @if ($contactEmail)
    <p class="mb-0">{{ ui('supplier_form.help_text') }} <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a></p>
    @endif
</div>
<p class="sf-secure-note">{{ ui('supplier_form.secure_note') }}</p>
