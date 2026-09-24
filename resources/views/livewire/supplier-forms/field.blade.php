@php
    use App\SupplierForms\FieldType;
    use App\SupplierForms\OptionLists;
    use App\Support\Countries;

    $key = $question->key;
    $id = 'sf-'.$key;
    $value = $answers[$key] ?? null;
    $hasError = $errors->has("answers.{$key}") || $errors->has("answers.{$key}.*") || $errors->has("uploads.{$key}") || $errors->has("uploads.{$key}.*");
    $isChoice = in_array($question->type, [FieldType::Choice, FieldType::Choices], true);
@endphp

@if ($definition->isVisible($question, $answers))
<div @class([$question->wide ? 'col-12' : 'col-12 col-md-6', 'sf-field', 'has-error' => $hasError]) wire:key="field-{{ $key }}">
    @if ($question->type !== FieldType::Consent)
    <div class="sf-label-row">
        <label class="sf-label" @if (! $isChoice) for="{{ $id }}" @else id="{{ $id }}-label" @endif>
            {{ $definition->label($question) }}@if ($question->required)<span class="sf-required" aria-hidden="true">*</span>@endif
        </label>
        @unless ($question->required)
        <span class="sf-optional">{{ ui('supplier_form.optional') }}</span>
        @endunless
    </div>
    @if ($question->type === FieldType::Choices)
    <div class="sf-help">{{ ui('supplier_form.several_answers') }}</div>
    @endif
    @endif

    @switch($question->type)
        @case(FieldType::Text)
        @case(FieldType::Url)
        @case(FieldType::Email)
            <div class="sf-input-icon">
                @if ($question->type !== FieldType::Text)
                <i class="fa {{ $question->type === FieldType::Url ? 'fa-globe' : 'fa-envelope' }}" aria-hidden="true"></i>
                @endif
                <input
                    type="{{ $question->type === FieldType::Email ? 'email' : 'text' }}"
                    id="{{ $id }}"
                    @class(['form-control', 'is-invalid' => $hasError])
                    wire:model.blur="answers.{{ $key }}"
                    maxlength="255"
                    @if ($question->type === FieldType::Url) inputmode="url" placeholder="https://" @endif
                    @if ($question->type === FieldType::Email) autocomplete="email" @endif
                >
            </div>
            @break

        @case(FieldType::Number)
            <input type="number" id="{{ $id }}" min="0" step="1" @class(['form-control', 'is-invalid' => $hasError]) wire:model.blur="answers.{{ $key }}">
            @break

        @case(FieldType::Date)
            <input type="date" id="{{ $id }}" max="{{ now()->toDateString() }}" @class(['form-control', 'is-invalid' => $hasError]) wire:model.blur="answers.{{ $key }}">
            @break

        @case(FieldType::Textarea)
            <textarea id="{{ $id }}" rows="4" maxlength="5000" @class(['form-control', 'is-invalid' => $hasError]) wire:model.blur="answers.{{ $key }}"></textarea>
            @break

        @case(FieldType::Phone)
            <div @class(['supplier-phone', 'is-invalid' => $hasError])>
                <div wire:ignore x-data="saboneaPhone(@js($value), @js(app()->getLocale()), @js(ui('supplier_form.phone_search')), @js("answers.{$key}"))">
                    <input type="tel" id="{{ $id }}" class="form-control" x-ref="input" autocomplete="tel">
                </div>
            </div>
            @break

        @case(FieldType::Country)
            <div class="sf-input-icon">
                <i class="fa fa-map-marker-alt" aria-hidden="true"></i>
                <select id="{{ $id }}" @class(['form-select', 'is-invalid' => $hasError]) wire:model.live="answers.{{ $key }}">
                    <option value="">{{ ui('form.select') }}</option>
                    @foreach (Countries::options() as $code => $name)
                    <option value="{{ $code }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            @break

        @case(FieldType::Countries)
            @if (filled($value))
            <div class="supplier-chips mb-2">
                @foreach ((array) $value as $code)
                <span class="supplier-chip" wire:key="chip-{{ $key }}-{{ $code }}">
                    {{ Countries::name($code) }}
                    <button type="button" class="supplier-chip-remove" wire:click="removeCountry('{{ $key }}', '{{ $code }}')" aria-label="{{ ui('supplier_form.remove') }} {{ Countries::name($code) }}">&times;</button>
                </span>
                @endforeach
            </div>
            @endif
            <div class="sf-input-icon">
                <i class="fa fa-plus" aria-hidden="true"></i>
                <select id="{{ $id }}" @class(['form-select', 'sf-add-country', 'is-invalid' => $hasError]) wire:change="addCountry('{{ $key }}', $event.target.value)" wire:key="add-country-{{ $key }}-{{ count((array) $value) }}">
                    <option value="">{{ ui('supplier_form.add_country') }}</option>
                    @foreach (Countries::options() as $code => $name)
                    @unless (in_array($code, (array) $value, true))
                    <option value="{{ $code }}">{{ $name }}</option>
                    @endunless
                    @endforeach
                </select>
            </div>
            @break

        @case(FieldType::Choice)
        @case(FieldType::Choices)
            @php
                $options = OptionLists::options($question->list);
                $selected = (array) $value;
            @endphp
            <div
                @class(['sf-choices', 'sf-choices-compact' => count($options) <= 3 || ! $question->wide])
                role="{{ $question->type === FieldType::Choice ? 'radiogroup' : 'group' }}"
                aria-labelledby="{{ $id }}-label"
            >
                @foreach ($options as $optionKey => $optionLabel)
                <label @class(['sf-choice', 'is-selected' => in_array($optionKey, $selected, true)]) for="{{ $id }}-{{ $optionKey }}" wire:key="option-{{ $key }}-{{ $optionKey }}">
                    <input
                        class="sf-choice-input"
                        type="{{ $question->type === FieldType::Choice ? 'radio' : 'checkbox' }}"
                        name="{{ $id }}"
                        id="{{ $id }}-{{ $optionKey }}"
                        value="{{ $optionKey }}"
                        wire:model.live="answers.{{ $key }}"
                    >
                    <span @class(['sf-choice-mark', 'is-radio' => $question->type === FieldType::Choice]) aria-hidden="true"><i class="fa fa-check"></i></span>
                    <span class="sf-choice-label">{{ $optionLabel }}</span>
                </label>
                @endforeach
            </div>
            @break

        @case(FieldType::Consent)
            <label @class(['sf-consent', 'is-selected' => (bool) $value]) for="{{ $id }}">
                <input class="sf-choice-input" type="checkbox" id="{{ $id }}" wire:model.live="answers.{{ $key }}">
                <span class="sf-choice-mark" aria-hidden="true"><i class="fa fa-check"></i></span>
                <span class="sf-consent-text">
                    {{ $definition->consentLabel($question) }}@if ($question->required)<span class="sf-required" aria-hidden="true">*</span>@endif
                </span>
            </label>
            @break

        @case(FieldType::Files)
            <div
                class="sf-dropzone"
                x-data="{ over: false, uploading: false, progress: 0 }"
                x-bind:class="{ 'is-over': over, 'is-uploading': uploading }"
                x-on:dragover="over = true"
                x-on:dragleave="over = false"
                x-on:drop="over = false"
                x-on:livewire-upload-start="uploading = true; progress = 0"
                x-on:livewire-upload-progress="progress = $event.detail.progress"
                x-on:livewire-upload-finish="uploading = false"
                x-on:livewire-upload-error="uploading = false"
            >
                <input
                    type="file"
                    id="{{ $id }}"
                    class="sf-dropzone-input"
                    multiple
                    accept=".pdf,.jpg,.jpeg,.png,.xlsx"
                    wire:model="uploads.{{ $key }}"
                    wire:key="upload-{{ $key }}-{{ count((array) $value) }}"
                >
                <div class="sf-dropzone-body" x-show="! uploading">
                    <i class="fa fa-cloud-upload-alt" aria-hidden="true"></i>
                    <div class="sf-dropzone-text">
                        <strong>{{ ui('supplier_form.drop_files') }}</strong>
                        <span>{{ ui('supplier_form.browse_files') }}</span>
                        <small>{{ ui('supplier_form.files_hint') }}</small>
                    </div>
                </div>
                <div class="sf-dropzone-progress" x-show="uploading" x-cloak>
                    <span>{{ ui('supplier_form.file_uploading') }} <span x-text="progress + ' %'"></span></span>
                    <div class="progress"><div class="progress-bar bg-sabonea-green" x-bind:style="'width: ' + progress + '%'"></div></div>
                </div>
            </div>
            @if (filled($value))
            <ul class="sf-files">
                @foreach ((array) $value as $index => $file)
                @php
                    $extension = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
                    $icon = match ($extension) {
                        'pdf' => 'fa-file-pdf',
                        'jpg', 'jpeg', 'png' => 'fa-file-image',
                        'xlsx' => 'fa-file-excel',
                        default => 'fa-file-alt',
                    };
                @endphp
                <li wire:key="file-{{ $key }}-{{ $index }}">
                    <i class="fa {{ $icon }}" aria-hidden="true"></i>
                    <span class="sf-file-name">{{ $file['name'] }}</span>
                    <small>{{ \Illuminate\Support\Number::fileSize($file['size'] ?? 0, precision: 1) }}</small>
                    <button type="button" class="supplier-chip-remove" wire:click="removeFile('{{ $key }}', {{ $index }})" aria-label="{{ ui('supplier_form.remove') }} {{ $file['name'] }}">&times;</button>
                </li>
                @endforeach
            </ul>
            @endif
            @break
    @endswitch

    @if ($help = $definition->help($question))
    <div class="sf-help sf-help-below">{{ $help }}</div>
    @endif

    @foreach ($definition->activeDetails($question, $answers) as $optionKey => $detailKey)
    <div class="sf-detail" wire:key="detail-{{ $detailKey }}">
        <label class="sf-detail-label" for="{{ $id }}-{{ $detailKey }}">
            <i class="fa fa-level-up-alt fa-rotate-90" aria-hidden="true"></i>
            {{ ui('supplier_form.please_specify') }} : {{ OptionLists::label($question->list, $optionKey) }}<span class="sf-required" aria-hidden="true">*</span>
        </label>
        <input
            type="text"
            id="{{ $id }}-{{ $detailKey }}"
            @class(['form-control', 'is-invalid' => $errors->has("answers.{$detailKey}")])
            wire:model.blur="answers.{{ $detailKey }}"
            maxlength="255"
        >
        @error("answers.{$detailKey}")<div class="sf-error"><i class="fa fa-exclamation-circle"></i>{{ $message }}</div>@enderror
    </div>
    @endforeach

    @foreach (["answers.{$key}", "answers.{$key}.*", "uploads.{$key}", "uploads.{$key}.*"] as $errorKey)
    @error($errorKey)<div class="sf-error"><i class="fa fa-exclamation-circle"></i>{{ $message }}</div>@enderror
    @endforeach
</div>
@endif
