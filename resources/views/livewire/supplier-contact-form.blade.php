<div id="supplier-form" class="sf">
    @if ($submitted)
    @include('livewire.supplier-forms.done', ['thanks' => $thanks, 'nextSteps' => $nextSteps])
    @else
    @php
        $sections = $definition->sections();
        $total = count($sections);
    @endphp
    <div class="row g-4 g-xl-5">
        <div class="col-lg-8">
            <form wire:submit="submit" novalidate>
                <div class="hp-field" aria-hidden="true">
                    <label for="sf-fax">Fax</label>
                    <input type="text" id="sf-fax" wire:model="fax" tabindex="-1" autocomplete="off">
                </div>

                @if ($isTest)
                <div class="sf-alert sf-alert-test" role="status"><i class="fa fa-flask"></i><span>{{ ui('supplier_form.test_mode') }}</span></div>
                @endif

                @if ($errors->any())
                <div class="sf-alert sf-alert-error" role="alert"><i class="fa fa-exclamation-triangle"></i><span>{{ $errors->has('form') ? $errors->first('form') : ui('supplier_form.errors_summary') }}</span></div>
                @endif

                {{-- Progress on small screens (the sidebar is hidden) --}}
                <div class="sf-mobile-progress d-lg-none">
                    <div class="d-flex justify-content-between small mb-2">
                        <strong>{{ ui('supplier_form.progress_title') }}</strong>
                        <span>{{ ui('supplier_form.progress_percent', ['percent' => $percent]) }}</span>
                    </div>
                    <div class="progress"><div class="progress-bar bg-sabonea-green" style="width: {{ $percent }}%"></div></div>
                </div>

                @foreach ($sections as $section)
                @php $state = $progress[$section->key]; @endphp
                <section class="sf-card" id="section-{{ $section->key }}" wire:key="section-{{ $section->key }}">
                    <header class="sf-card-header">
                        <span class="sf-card-icon"><i class="{{ $section->icon }}" aria-hidden="true"></i></span>
                        <div class="sf-card-heading">
                            <span class="sf-card-eyebrow">{{ ui('supplier_form.section_count', ['current' => $loop->iteration, 'total' => $total]) }}</span>
                            <h3 class="sf-card-title">{{ $definition->sectionTitle($section) }}</h3>
                        </div>
                        @if ($state['total'] === 0)
                        @elseif ($state['complete'])
                        <span class="sf-card-status is-complete" title="{{ $state['answered'] }}/{{ $state['total'] }}"><i class="fa fa-check"></i></span>
                        @else
                        <span class="sf-card-status">{{ $state['answered'] }}/{{ $state['total'] }}</span>
                        @endif
                    </header>
                    <div class="row g-4">
                        @foreach ($section->questions as $question)
                        @include('livewire.supplier-forms.field', ['question' => $question])
                        @endforeach
                    </div>
                </section>
                @endforeach

                <div class="sf-submit-bar">
                    <p class="mb-0 small">{{ ui('supplier_form.required_hint') }}</p>
                    <button class="btn btn-primary sf-submit" type="submit" wire:loading.attr="disabled" wire:target="submit">
                        <span wire:loading.remove wire:target="submit">{{ ui('supplier_form.submit_contact') }}<i class="fa fa-paper-plane ms-2"></i></span>
                        <span wire:loading wire:target="submit"><span class="spinner-border spinner-border-sm me-2" role="status"></span>{{ ui('supplier_form.sending') }}</span>
                    </button>
                </div>
            </form>
        </div>

        <div class="col-lg-4 d-none d-lg-block">
            <aside class="sf-sidebar">
                <div class="sf-side-card">
                    <div class="sf-side-title">{{ ui('supplier_form.progress_title') }}</div>
                    <div class="sf-side-percent"><strong>{{ $percent }}</strong><span>%</span></div>
                    <div class="progress sf-side-bar"><div class="progress-bar bg-sabonea-green" style="width: {{ $percent }}%"></div></div>
                    <ol class="sf-side-steps">
                        @foreach ($sections as $section)
                        @php $state = $progress[$section->key]; @endphp
                        <li>
                            <a href="#section-{{ $section->key }}" @class(['sf-side-step', 'is-complete' => $state['complete']])>
                                <span class="sf-side-step-icon">
                                    @if ($state['complete'])<i class="fa fa-check"></i>@else<i class="{{ $section->icon }}"></i>@endif
                                </span>
                                <span class="sf-side-step-title">{{ $definition->sectionTitle($section) }}</span>
                                @if ($state['total'] > 0)<span class="sf-side-step-count">{{ $state['answered'] }}/{{ $state['total'] }}</span>@endif
                            </a>
                        </li>
                        @endforeach
                    </ol>
                    <div class="sf-side-meta"><i class="fa fa-clock"></i>{{ ui('supplier_form.estimated_time') }}</div>
                </div>
                @include('livewire.supplier-forms.help-card')
            </aside>
        </div>
    </div>
    @endif
</div>
