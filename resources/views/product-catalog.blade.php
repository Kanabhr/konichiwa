@extends('layouts.redesign')

@section('title', 'Our Products | Konichiwa')
@section('meta_description', 'Explore our comprehensive range of high-quality industrial chemicals and innovative solutions for various sectors.')

@section('content')
    @include('partials.redesign-breadcrumb', ['pageTitle' => 'Product Catalog', 'breadcrumbActive' => 'Products'])

    <section class="rd-products rts-section-gap">
        <div class="container">
            <div class="row g-4 justify-content-center">
                @forelse($products as $product)
                    <div class="col-lg-4 col-md-6 col-sm-10 col-12">
                        <article class="rd-product-card">
                            <div class="rd-product-media">
                                <img src="{{ $product['full_image_url'] }}"
                                     alt="{{ $product['product_name'] }}"
                                     @if(!$loop->first) loading="lazy" @endif
                                     decoding="async">
                            </div>
                            <div class="rd-product-body">
                                <h2 class="rd-product-name">{{ $product['product_name'] }}</h2>
                                <a href="#" class="rts-btn btn-primary w-100">View details</a>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="fal fa-shopping-basket fa-3x mb-3 rd-blog-result" aria-hidden="true"></i>
                        <p class="rd-blog-result">No products are currently available in the catalog.</p>
                        <a href="{{ route('contact') }}" class="rts-btn btn-primary mt-3">Contact us for inquiries</a>
                    </div>
                @endforelse
            </div>

            @if($totalPages > 1)
                <nav class="rd-product-pages" aria-label="Product pages">
                    <div class="pagination d-flex justify-content-center gap-2">

                        {{-- Previous button --}}
                        @if($currentPage > 1)
                            <a href="{{ route('products.index', ['page' => $currentPage - 1]) }}" class="page-item-btn" aria-label="Previous page">
                                <i class="fal fa-angle-double-left" aria-hidden="true"></i>
                            </a>
                        @else
                            <span class="page-item-btn disabled" aria-hidden="true">
                                <i class="fal fa-angle-double-left"></i>
                            </span>
                        @endif

                        {{-- Page number buttons --}}
                        @for($i = 1; $i <= $totalPages; $i++)
                            @if($i === $currentPage)
                                <span class="page-item-btn active" aria-current="page">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</span>
                            @else
                                <a href="{{ route('products.index', ['page' => $i]) }}" class="page-item-btn" aria-label="Page {{ $i }}">
                                    {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                </a>
                            @endif
                        @endfor

                        {{-- Next button --}}
                        @if($currentPage < $totalPages)
                            <a href="{{ route('products.index', ['page' => $currentPage + 1]) }}" class="page-item-btn" aria-label="Next page">
                                <i class="fal fa-angle-double-right" aria-hidden="true"></i>
                            </a>
                        @else
                            <span class="page-item-btn disabled" aria-hidden="true">
                                <i class="fal fa-angle-double-right"></i>
                            </span>
                        @endif

                    </div>
                </nav>
            @endif
        </div>
    </section>
@endsection