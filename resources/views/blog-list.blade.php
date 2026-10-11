@extends('layouts.redesign')

@section('title', 'Blog | Konichiwa')

@section('content')
    @include('partials.redesign-breadcrumb', ['pageTitle' => 'Latest Posts', 'breadcrumbActive' => 'Blog'])

    <section class="rd-blog-list rts-section-gap">
        <div class="container">
            <div class="row g-5">

                {{-- Posts --}}
                <div class="col-xl-8 col-12">
                    @if($search)
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
                            <p class="mb-0 rd-blog-result">
                                Showing results for <strong>"{{ $search }}"</strong>
                                &mdash; {{ $blogs->total() }} {{ Str::plural('post', $blogs->total()) }} found
                            </p>
                            <a href="{{ route('blog-list') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fal fa-times me-1" aria-hidden="true"></i> Clear
                            </a>
                        </div>
                    @endif

                    @forelse($blogs as $blog)
                        <article class="rd-blog-card">
                            <a class="rd-blog-thumb" href="{{ route('blog-details', $blog->slug) }}" tabindex="-1" aria-hidden="true">
                                <img src="{{ $blog->image ? asset($blog->image) : asset('assets/images/blog/blog6.webp') }}"
                                     alt=""
                                     @if(!$loop->first) loading="lazy" @endif
                                     decoding="async">
                            </a>
                            <div class="rd-blog-body">
                                <ul class="rd-blog-meta">
                                    <li><i class="far fa-user-circle" aria-hidden="true"></i>by {{ $blog->author ?? 'Admin' }}</li>
                                    <li><i class="far fa-clock" aria-hidden="true"></i>{{ $blog->created_at->format('d M, Y') }}</li>
                                    <li><i class="far fa-tags" aria-hidden="true"></i>{{ $blog->category ?? 'Business' }}</li>
                                </ul>
                                <h2 class="rd-blog-title">
                                    <a href="{{ route('blog-details', $blog->slug) }}">{{ $blog->title }}</a>
                                </h2>
                                <p class="rd-blog-excerpt">
                                    {{ $blog->excerpt ?? Str::limit(strip_tags($blog->content), 150) }}
                                </p>
                                <a class="rts-btn btn-primary" href="{{ route('blog-details', $blog->slug) }}">Read Details</a>
                            </div>
                        </article>
                    @empty
                        <div class="text-center py-5">
                            <i class="far fa-folder-open fa-3x mb-3 rd-blog-result" aria-hidden="true"></i>
                            @if($search)
                                <p>No results found for <strong>"{{ $search }}"</strong>.</p>
                                <a href="{{ route('blog-list') }}" class="rts-btn btn-primary">Clear Search</a>
                            @else
                                <p>No blog posts found at the moment.</p>
                            @endif
                        </div>
                    @endforelse

                    <nav class="rd-pagination" aria-label="Blog pages">
                        {{ $blogs->links() }}
                    </nav>
                </div>

                {{-- Sidebar --}}
                <aside class="col-xl-4 col-12" aria-label="Blog sidebar">
                    <div class="rd-widget">
                        <h2 class="rd-widget-title">Search</h2>
                        <form action="{{ route('blog-list') }}" method="GET" role="search">
                            <div class="rd-search">
                                <label for="blog-search" class="visually-hidden">Search blog posts</label>
                                <input id="blog-search" type="text" name="search" value="{{ $search }}" placeholder="Enter keyword">
                                <button type="submit" aria-label="Search"><i class="fal fa-search" aria-hidden="true"></i></button>
                            </div>
                        </form>
                    </div>

                    <div class="rd-widget">
                        <h2 class="rd-widget-title">Categories</h2>
                        <ul class="rd-cat-list">
                            @forelse($categories as $cat)
                                <li>
                                    <a href="{{ route('blog-list', ['category' => $cat]) }}" class="{{ request('category') == $cat ? 'active' : '' }}">
                                        {{ $cat }} <i class="far fa-long-arrow-right" aria-hidden="true"></i>
                                    </a>
                                </li>
                            @empty
                                <li><span class="rd-blog-result small">No categories yet.</span></li>
                            @endforelse
                            @if(request('category'))
                                <li><a href="{{ route('blog-list') }}" class="rd-cat-clear">Clear filter</a></li>
                            @endif
                        </ul>
                    </div>

                    <div class="rd-widget">
                        <h2 class="rd-widget-title">Recent Posts</h2>
                        @foreach($recentPosts as $recent)
                            <div class="rd-recent">
                                <a class="rd-recent-thumb" href="{{ route('blog-details', $recent->slug) }}" tabindex="-1" aria-hidden="true">
                                    <img src="{{ $recent->image ? asset($recent->image) : asset('assets/images/blog/blog6.webp') }}" alt="" loading="lazy" decoding="async">
                                </a>
                                <div>
                                    <p class="rd-recent-date"><i class="fal fa-clock" aria-hidden="true"></i> {{ $recent->created_at->format('d M, Y') }}</p>
                                    <a class="rd-recent-title" href="{{ route('blog-details', $recent->slug) }}">{{ Str::limit($recent->title, 40) }}</a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="rd-widget rd-widget-cta">
                        <a href="{{ route('home') }}" aria-label="Konichiwa Home">
                            <img src="{{ asset('assets/images/logo/logo10.png') }}" alt="Konichiwa Chemicals" width="150" height="56">
                        </a>
                        <h2 class="rd-widget-title">Need help? We are here to help you</h2>
                        <a class="rts-btn btn-primary" href="{{ route('contact') }}">Contact Us</a>
                    </div>
                </aside>

            </div>
        </div>
    </section>
@endsection