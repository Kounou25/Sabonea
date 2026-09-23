@if ($section)
    <!-- CTA Banner Start -->
    <div class="container-fluid bg-sabonea-purple-dark py-5 my-5">
        <div class="container py-4">
            <div class="row align-items-center g-4">
                <div class="col-lg-{{ $textColumns ?? 8 }}">
                    <h2 class="text-white text-uppercase mb-2">{{ $section->t('title') }}</h2>
                    <p class="text-white-50 mb-0">{{ $section->md('body') }}</p>
                </div>
                <div class="col-lg-{{ 12 - ($textColumns ?? 8) }} text-lg-end">
                    @if ($section->t('cta2_label'))
                    <a href="{{ \App\Support\SiteLink::url($section->cta_url) }}" class="btn navbar-cta py-3 px-4 me-2 mb-2">{{ $section->t('cta_label') }}</a>
                    <a href="{{ \App\Support\SiteLink::url($section->cta2_url) }}" class="btn btn-outline-white py-3 px-4 mb-2">{{ $section->t('cta2_label') }}</a>
                    @else
                    <a href="{{ \App\Support\SiteLink::url($section->cta_url) }}" class="btn navbar-cta py-3 px-5">{{ $section->t('cta_label') }}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- CTA Banner End -->
@endif
