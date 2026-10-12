{{-- Farmer Stories Section. Included in the home page. Receives $farmerStories. --}}
@if($farmerStories->isNotEmpty())
<section class="rd-farmers rts-section-gap" id="farmer-stories-section">
    <div class="container">
        <div class="rd-farmers-head text-center">
            <span class="rd-label">Our farmers</span>
            <h2 class="rd-about-title">Farmer Success Stories</h2>
            <p>Real farmers, real results. Hear from the growers who trust Konichiwa products in their fields.</p>
        </div>

        <div class="row g-4 justify-content-center" id="farmerStoriesGrid">
            @foreach($farmerStories as $index => $story)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-10 col-12 farmer-story-col"
                     data-story-index="{{ $index }}"
                     @if($index >= 4) style="display: none;" @endif>
                    <article class="rd-farmer-card">
                        <div class="rd-farmer-photo">
                            @if($story->image)
                                <img src="{{ asset($story->image) }}"
                                     alt="{{ $story->farmer_name }}"
                                     @if(!$loop->first) loading="lazy" @endif
                                     decoding="async">
                            @else
                                <span class="rd-farmer-fallback" aria-hidden="true"><i class="fas fa-user"></i></span>
                            @endif
                        </div>
                        <div class="rd-farmer-body">
                            @if($story->testimonial)
                                <blockquote class="rd-farmer-quote">{{ $story->testimonial }}</blockquote>
                            @endif
                            <div class="rd-farmer-meta">
                                <h3 class="rd-farmer-name">{{ $story->farmer_name }}</h3>
                                @if($story->location)
                                    <p class="rd-farmer-location"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> {{ $story->location }}</p>
                                @endif
                                @if($story->suggested_product)
                                    <span class="rd-farmer-tag">{{ $story->suggested_product }}</span>
                                @endif
                            </div>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>

        @if($farmerStories->count() > 4)
            <div class="farmer-stories-actions mt--40 text-center d-flex align-items-center justify-content-center gap-3 flex-wrap">
                <button type="button" class="rts-btn btn-primary" id="btnLoadMoreStories">
                    Load More <i class="fas fa-chevron-down ms-2" aria-hidden="true"></i>
                </button>
                <button type="button" class="rts-btn btn-outline-dark" id="btnViewLessStories" style="display: none !important;">
                    View Less <i class="fas fa-chevron-up ms-2" aria-hidden="true"></i>
                </button>
            </div>
        @endif
    </div>
</section>
@endif