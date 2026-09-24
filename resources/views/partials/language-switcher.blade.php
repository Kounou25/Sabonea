@php
    $currentLanguage = $activeLanguages->firstWhere('code', app()->getLocale());
@endphp

@if ($activeLanguages->count() > 1 && $currentLanguage)
<div class="dropdown lang-switcher ms-auto ms-xl-0 order-xl-last">
    <button
        type="button"
        class="lang-switcher-toggle"
        id="languageSwitcher"
        data-bs-toggle="dropdown"
        data-bs-offset="0,10"
        aria-expanded="false"
        aria-label="{{ ui('layout.choose_language') }} : {{ $currentLanguage->name }}"
    >
        <img class="lang-flag" src="{{ $currentLanguage->flagUrl() }}" alt="" width="22" height="16">
        <span class="lang-switcher-code">{{ strtoupper($currentLanguage->code) }}</span>
        <i class="bi bi-chevron-down lang-switcher-chevron" aria-hidden="true"></i>
    </button>

    <ul class="dropdown-menu dropdown-menu-end lang-switcher-menu" aria-labelledby="languageSwitcher">
        <li class="lang-switcher-title">{{ ui('layout.choose_language') }}</li>
        @foreach ($activeLanguages as $language)
        <li>
            <a
                href="{{ locale_url($language->code) }}"
                hreflang="{{ $language->code }}"
                lang="{{ $language->code }}"
                @class(['dropdown-item', 'active' => $language->code === $currentLanguage->code])
                @if ($language->code === $currentLanguage->code) aria-current="true" @endif
            >
                <img class="lang-flag" src="{{ $language->flagUrl() }}" alt="" width="24" height="18">
                <span class="lang-switcher-name">{{ $language->name }}</span>
                <span class="lang-switcher-item-code">{{ strtoupper($language->code) }}</span>
                @if ($language->code === $currentLanguage->code)
                <i class="bi bi-check2 lang-switcher-check" aria-hidden="true"></i>
                @endif
            </a>
        </li>
        @endforeach
    </ul>
</div>
@endif
