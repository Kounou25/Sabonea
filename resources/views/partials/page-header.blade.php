    <header @class(['page-head', 'has-media' => $page->header_image])>
        <div class="container">
            <nav class="page-crumbs">
                <a href="{{ route('accueil') }}">{{ ui('nav.home') }}</a><span class="page-crumbs-sep" aria-hidden="true">/</span><span aria-current="page">{{ $page->t('breadcrumb') }}</span>
            </nav>
            <h1 class="t-page">{{ $page->t('header_title') }}</h1>
        </div>
        @if ($page->header_image)
        <div class="page-head-media">
            <img src="{{ \App\Support\Media::url($page->header_image) }}" alt="{{ $page->t('header_image_alt') }}">
        </div>
        @endif
    </header>
