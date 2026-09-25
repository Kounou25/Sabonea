{{-- Confirmation shown once a supplier form is sent: thank-you message and next steps. --}}
<div class="sf-done">
    <div class="sf-done-icon"><i class="fa fa-check" aria-hidden="true"></i></div>
    <h2 class="sf-done-title">{{ $thanks?->t('title') }}</h2>
    <p class="sf-done-text">{{ $thanks?->md('body') }}</p>

    @if ($nextSteps && $nextSteps->items->isNotEmpty())
    <div class="sf-next">
        <h3 class="sf-next-title">{{ $nextSteps->t('title') }}</h3>
        <ol class="sf-next-steps">
            @foreach ($nextSteps->items as $item)
            <li>
                <span class="sf-next-number">{{ $loop->iteration }}</span>
                <div>
                    <strong>{{ $item->t('title') }}</strong>
                    <span>{{ $item->md('text') }}</span>
                </div>
            </li>
            @endforeach
        </ol>
    </div>
    @endif

    <a href="{{ route('accueil') }}" class="btn btn-outline-primary">{{ ui('supplier_form.back_home') }}</a>
</div>
