{{-- Preview of the supplier profile PDF, reloaded when the version or the language changes. --}}
<div
    x-data="{ loading: true }"
    x-init="setTimeout(() => loading = false, 20000)"
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
        src="{{ $url }}#view=FitH"
        title="Aperçu de la fiche fournisseur"
        style="display: block; width: 100%; height: calc(100vh - 330px); min-height: 420px; border: 0;"
        x-on:load="loading = false"
    ></iframe>
</div>
