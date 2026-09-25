{{--
    Preview of the supplier profile PDF, reloaded when an option changes. The PDF is requested a moment later,
    so that the scripts of the form fields load first (a single-process server would queue them behind the PDF).
--}}
<div
    x-data="{ loading: true, src: null }"
    x-init="setTimeout(() => src = $el.dataset.url, 500); setTimeout(() => loading = false, 30000)"
    data-url="{{ $url }}#view=FitH"
    style="position: relative; border-radius: 12px; overflow: hidden; background: #f3f0f8; border: 1px solid rgba(0, 0, 0, .08);"
    wire:key="profile-preview-{{ md5($url) }}"
>
    <div x-show="loading" style="position: absolute; inset: 0; pointer-events: none;">
        <div style="display: flex; height: 100%; align-items: center; justify-content: center; gap: 10px; color: #6c6280; font-size: 14px;">
            <x-filament::loading-indicator style="width: 20px; height: 20px;" />
            Génération du document…
        </div>
    </div>
    <iframe
        x-bind:src="src"
        title="Aperçu de la fiche fournisseur"
        style="display: block; width: 100%; height: calc(100vh - 250px); min-height: 480px; border: 0;"
        x-on:load="if (src) loading = false"
    ></iframe>
</div>
