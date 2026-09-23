    <!-- Page Header Start -->
    @if ($page->header_image)
    <div class="page-header hero-overlay">
        <img class="bg-img" src="{{ \App\Support\Media::url($page->header_image) }}" alt="{{ $page->t('header_image_alt') }}">
        <div class="container page-header-inner">
            <h1 class="display-4 text-white mb-3">{{ $page->t('header_title') }}</h1>
            <nav class="breadcrumb bg-transparent m-0">
                <a href="{{ route('accueil') }}">{{ ui('nav.home') }}</a><span class="text-white mx-2">/</span><span class="active">{{ $page->t('breadcrumb') }}</span>
            </nav>
        </div>
    </div>
    @else
    <div class="page-header page-header-gradient d-flex align-items-center">
        <div class="container page-header-inner position-relative" style="z-index:2;">
            <h1 class="display-4 text-white mb-3">{{ $page->t('header_title') }}</h1>
            <nav class="breadcrumb bg-transparent m-0">
                <a href="{{ route('accueil') }}">{{ ui('nav.home') }}</a><span class="text-white mx-2">/</span><span class="active">{{ $page->t('breadcrumb') }}</span>
            </nav>
        </div>
        <i class="fa fa-leaf leaf-deco"></i>
    </div>
    @endif
    <!-- Page Header End -->
