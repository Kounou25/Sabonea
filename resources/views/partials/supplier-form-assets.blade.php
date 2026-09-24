@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/intl-tel-input@29.5.2/dist/css/intlTelInput.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@29.5.2/dist/js/intlTelInputWithUtils.min.js"></script>
    <script src="{{ asset('js/supplier-forms.js') }}?v={{ filemtime(public_path('js/supplier-forms.js')) }}"></script>
@endpush
