@php
    $crumbTitle  = $pageTitle ?? '';
    $crumbActive = $breadcrumbActive ?? $crumbTitle;
@endphp
<section class="rd-crumb" aria-label="Page header">
    <div class="container">
        <h1 class="rd-crumb-title">{{ $crumbTitle }}</h1>
        <nav aria-label="Breadcrumb">
            <ol class="rd-crumb-trail" itemscope itemtype="https://schema.org/BreadcrumbList">
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a href="{{ url('/') }}" itemprop="item"><span itemprop="name">Home</span></a>
                    <meta itemprop="position" content="1">
                </li>
                <li class="is-active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <span itemprop="name">{{ $crumbActive }}</span>
                    <meta itemprop="position" content="2">
                </li>
            </ol>
        </nav>
    </div>
</section>
