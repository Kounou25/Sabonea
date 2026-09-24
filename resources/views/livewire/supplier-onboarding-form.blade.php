<div id="supplier-form" class="sf">
    @php
        $intro = $page?->section('intro');
        $invalid = $page?->section('invalid');
        $total = count($sections);
        $current = $sections[$step];
        $currentState = $progress[$current->key];
        $isLastStep = $step === $total - 1;
    @endphp

    @if (! $isValid)
    <div class="sf-done">
        <div class="sf-done-icon is-locked"><i class="fa fa-lock" aria-hidden="true"></i></div>
        <h2 class="sf-done-title">{{ $invalid?->t('title') }}</h2>
        <p class="sf-done-text">{{ $invalid?->md('body') }}</p>
        @if ($invalid?->t('cta_label'))
        <a href="{{ \App\Support\SiteLink::url($invalid->cta_url) }}" class="btn btn-primary py-3 px-5">{{ $invalid->t('cta_label') }}</a>
        @endif
    </div>
    @elseif ($submitted)
    @include('livewire.supplier-forms.done', ['thanks' => $page?->section('thanks'), 'nextSteps' => $page?->section('next_steps')])
    @else
    @if ($intro && $step === 0)
    <div class="sf-intro">
        <h6 class="text-sabonea-orange text-uppercase mb-2">{{ $intro->t('eyebrow') }}</h6>
        <h2 class="mb-3">{{ $intro->t('title') }}</h2>
        <p class="mb-0">{{ $intro->md('body') }}</p>
    </div>
    @endif

    <div class="row g-4 g-xl-5">
        {{-- Steps --}}
        <div class="col-lg-4 d-none d-lg-block">
            <aside class="sf-sidebar">
                <div class="sf-side-card">
                    <div class="sf-side-title">{{ ui('supplier_form.progress_title') }}</div>
                    <div class="sf-side-percent"><strong>{{ $percent }}</strong><span>%</span></div>
                    <div class="progress sf-side-bar"><div class="progress-bar bg-sabonea-green" style="width: {{ $percent }}%"></div></div>
                    <ol class="sf-side-steps">
                        @foreach ($sections as $index => $section)
                        @php
                            $state = $progress[$section->key];
                            $isDone = $index !== $step && $index <= $furthestStep && $state['complete'];
                        @endphp
                        <li wire:key="side-step-{{ $section->key }}">
                            <button
                                type="button"
                                @class(['sf-side-step', 'is-current' => $index === $step, 'is-complete' => $isDone])
                                wire:click="goToStep({{ $index }})"
                                @disabled($index > $furthestStep)
                                @if ($index === $step) aria-current="step" @endif
                            >
                                <span class="sf-side-step-icon">
                                    @if ($isDone)<i class="fa fa-check"></i>@else{{ $index + 1 }}@endif
                                </span>
                                <span class="sf-side-step-title">{{ $definition->sectionTitle($section) }}</span>
                                @if ($index <= $furthestStep)
                                @if ($state['total'] > 0)<span class="sf-side-step-count">{{ $state['answered'] }}/{{ $state['total'] }}</span>@endif
                                @endif
                            </button>
                        </li>
                        @endforeach
                    </ol>
                    <div class="sf-side-meta">
                        @if ($savedAt)
                        <i class="fa fa-check-circle text-sabonea-green"></i>{{ ui('supplier_form.draft_saved', ['time' => $savedAt]) }}
                        @else
                        <i class="fa fa-clock"></i>{{ ui('supplier_form.estimated_time_onboarding') }}
                        @endif
                    </div>
                </div>
                @include('livewire.supplier-forms.help-card')
            </aside>
        </div>

        {{-- Current step --}}
        <div class="col-lg-8">
            <div class="sf-mobile-progress d-lg-none">
                <div class="d-flex justify-content-between small mb-2">
                    <strong>{{ ui('supplier_form.step', ['current' => $step + 1, 'total' => $total]) }}</strong>
                    <span>
                        @if ($savedAt)<i class="fa fa-check-circle text-sabonea-green me-1"></i>{{ ui('supplier_form.draft_saved', ['time' => $savedAt]) }}@endif
                    </span>
                </div>
                <div class="progress"><div class="progress-bar bg-sabonea-green" style="width: {{ round(($step + 1) / $total * 100) }}%"></div></div>
            </div>

            @if ($errors->any())
            <div class="sf-alert sf-alert-error" role="alert"><i class="fa fa-exclamation-triangle"></i><span>{{ ui('supplier_form.errors_summary') }}</span></div>
            @endif

            <form wire:submit="{{ $isLastStep ? 'submit' : 'next' }}" novalidate>
                <section class="sf-card" wire:key="section-{{ $current->key }}">
                    <header class="sf-card-header">
                        <span class="sf-card-icon"><i class="{{ $current->icon }}" aria-hidden="true"></i></span>
                        <div class="sf-card-heading">
                            <span class="sf-card-eyebrow">{{ ui('supplier_form.step', ['current' => $step + 1, 'total' => $total]) }}</span>
                            <h3 class="sf-card-title">{{ $definition->sectionTitle($current) }}</h3>
                        </div>
                        @if ($currentState['total'] === 0)
                        @elseif ($currentState['complete'])
                        <span class="sf-card-status is-complete"><i class="fa fa-check"></i></span>
                        @else
                        <span class="sf-card-status">{{ $currentState['answered'] }}/{{ $currentState['total'] }}</span>
                        @endif
                    </header>

                    @if ($step === 0)
                    <div class="sf-note"><i class="fa fa-magic"></i><span>{{ ui('supplier_form.prefilled') }}</span></div>
                    @elseif ($current->key === 'documents')
                    <div class="sf-note"><i class="fa fa-paperclip"></i><span>{{ ui('supplier_form.files_intro') }}</span></div>
                    @endif

                    <div class="row g-4">
                        @foreach ($current->questions as $question)
                        @include('livewire.supplier-forms.field', ['question' => $question])
                        @endforeach
                    </div>

                    <footer class="sf-card-footer">
                        <div>
                            @if ($step > 0)
                            <button type="button" class="btn sf-btn-ghost" wire:click="previous"><i class="fa fa-arrow-left me-2"></i>{{ ui('supplier_form.previous') }}</button>
                            @endif
                        </div>
                        <button class="btn {{ $isLastStep ? 'btn-sabonea-green' : 'btn-primary' }} sf-submit" type="submit" wire:loading.attr="disabled" wire:target="next,submit">
                            <span wire:loading.remove wire:target="next,submit">
                                @if ($isLastStep)
                                {{ ui('supplier_form.submit_onboarding') }}<i class="fa fa-paper-plane ms-2"></i>
                                @else
                                {{ ui('supplier_form.next') }}<i class="fa fa-arrow-right ms-2"></i>
                                @endif
                            </span>
                            <span wire:loading wire:target="next,submit"><span class="spinner-border spinner-border-sm me-2" role="status"></span>{{ ui('supplier_form.sending') }}</span>
                        </button>
                    </footer>
                </section>
            </form>
            <p class="small text-muted mt-3 mb-0">{{ ui('supplier_form.required_hint') }} {{ ui('supplier_form.draft_info') }}</p>
        </div>
    </div>
    @endif
</div>
