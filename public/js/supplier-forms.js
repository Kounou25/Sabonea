/*
 * Supplier forms (Livewire): international phone field and scrolling.
 */
(function () {
    "use strict";

    // Default country of the phone field, from the site language.
    var DEFAULT_COUNTRY = { fr: 'fr', de: 'de', zh: 'cn', en: '' };

    document.addEventListener('alpine:init', function () {
        window.Alpine.data('saboneaPhone', function (initialValue, locale, searchPlaceholder, statePath) {
            // Kept out of Alpine's reactive data on purpose: Alpine wraps its data in a Proxy,
            // and intl-tel-input's private class fields (#...) cannot be read through a Proxy.
            var iti = null;

            return {
                init: function () {
                    var input = this.$refs.input;
                    var wire = this.$wire;

                    input.value = initialValue || '';

                    iti = window.intlTelInput(input, {
                        countryNameLocale: locale === 'zh' ? 'zh-CN' : locale,
                        initialCountry: initialValue ? '' : (DEFAULT_COUNTRY[locale] || ''),
                        countryOrder: ['fr', 'de', 'cn', 'gb', 'us'],
                        separateDialCode: true,
                        strictMode: true,
                    });

                    var search = input.closest('.iti') && input.closest('.iti').querySelector('.iti__search-input');
                    if (search && searchPlaceholder) {
                        search.setAttribute('placeholder', searchPlaceholder);
                    }

                    // The international number (+33...) is what the form stores and validates.
                    var sync = function () {
                        var number = iti.getNumber() || input.value.trim();
                        wire.set(statePath, number);
                    };

                    input.addEventListener('change', sync);
                    input.addEventListener('countrychange', function () {
                        if (input.value.trim() !== '') {
                            sync();
                        }
                    });
                },
            };
        });
    });

    var scrollTo = function (selector, block) {
        window.setTimeout(function () {
            var element = document.querySelector(selector);

            if (element) {
                element.scrollIntoView({ behavior: 'smooth', block: block });
            }
        }, 60);
    };

    document.addEventListener('livewire:init', function () {
        window.Livewire.on('supplier-form-invalid', function () {
            scrollTo('#supplier-form .sf-error, #supplier-form .is-invalid', 'center');
        });
        window.Livewire.on('supplier-form-step', function () {
            scrollTo('#supplier-form', 'start');
        });
        window.Livewire.on('supplier-form-done', function () {
            scrollTo('#supplier-form', 'start');
        });
    });
})();
