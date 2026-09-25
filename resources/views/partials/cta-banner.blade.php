@if ($section)
    <section class="cta-band">
        <div class="container">
            <div class="cta-band-inner">
                <div>
                    <h2 class="t-h2">{{ $section->t('title') }}</h2>
                    <p>{{ $section->md('body') }}</p>
                </div>
                <div class="cta-band-actions">
                    <a href="{{ \App\Support\SiteLink::url($section->cta_url) }}" class="btn btn-accent">{{ $section->t('cta_label') }}</a>
                    @if ($section->t('cta2_label'))
                    <a href="{{ \App\Support\SiteLink::url($section->cta2_url) }}" class="link-more">{{ $section->t('cta2_label') }}</a>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endif
