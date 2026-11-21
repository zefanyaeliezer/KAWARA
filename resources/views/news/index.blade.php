@extends('layouts.app')

@section('title', 'KAWARA — Informasi Lokal, Perspektif Aktual')

@section('categories')
    <div class="category-list">
        <a
            href="{{ route('news.index', $search !== '' ? ['q' => $search] : []) }}"
            class="category-pill {{ $selectedCategory === '' ? 'active' : '' }}"
        >
            Semua Berita
        </a>
        @foreach($categories as $category)
            <a
                href="{{ route('news.index', array_filter(['category' => $category, 'q' => $search])) }}"
                class="category-pill {{ $selectedCategory === $category ? 'active' : '' }}"
            >
                {{ $category }}
            </a>
        @endforeach
    </div>
@endsection

@section('content')
    <div class="container">
        @if($featuredNews)
            <article class="featured-card">
                <a href="{{ route('news.show', $featuredNews->slug) }}" class="featured-media">
                    @if($featuredNews->thumbnail_url)
                        <img src="{{ $featuredNews->thumbnail_url }}" alt="{{ $featuredNews->title }}">
                    @else
                        <div class="media-placeholder">KAWARA</div>
                    @endif
                </a>
                <div class="featured-body">
                    <span class="badge">{{ $featuredNews->category }}</span>
                    <h1 class="featured-title">
                        <a href="{{ route('news.show', $featuredNews->slug) }}">{{ $featuredNews->title }}</a>
                    </h1>
                    <p class="featured-excerpt">{{ $featuredNews->summary }}</p>
                    <div class="meta-line">
                        <span>{{ $featuredNews->author?->name ?? 'Redaksi' }}</span>
                        <span>&bull;</span>
                        <time datetime="{{ $featuredNews->published_at?->toIso8601String() }}">
                            {{ $featuredNews->published_at?->format('d M Y, H:i') }} WIB
                        </time>
                    </div>
                </div>
            </article>
        @endif

        <div class="section-head">
            <h2 class="section-title">
                @if($search !== '')
                    Hasil pencarian: "{{ $search }}"
                @elseif($selectedCategory !== '')
                    Topik: {{ $selectedCategory }}
                @else
                    Berita Terbaru
                @endif
            </h2>
            @if($search !== '' || $selectedCategory !== '')
                <a href="{{ route('news.index') }}" class="section-sub">Reset filter</a>
            @endif
        </div>

        @if($newsList->isEmpty() && ! $featuredNews)
            <div class="empty-state">
                <h2>Belum ada berita</h2>
                <p>
                    @if($search !== '' || $selectedCategory !== '')
                        Tidak ditemukan berita yang sesuai dengan filter Anda.
                    @else
                        Berita terbaru dari redaksi akan segera tampil di halaman ini.
                    @endif
                </p>
            </div>
        @elseif($newsList->isNotEmpty())
            <div class="news-grid">
                @foreach($newsList as $item)
                    <article class="news-card">
                        <a href="{{ route('news.show', $item->slug) }}" class="news-card-media">
                            @if($item->thumbnail_url)
                                <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}">
                            @else
                                <div class="media-placeholder">KAWARA</div>
                            @endif
                        </a>
                        <div class="news-card-body">
                            <span class="badge">{{ $item->category }}</span>
                            <h3 class="news-card-title">
                                <a href="{{ route('news.show', $item->slug) }}">{{ $item->title }}</a>
                            </h3>
                            <p class="news-card-excerpt">{{ $item->summary }}</p>
                            <div class="meta-line">
                                <span>{{ $item->author?->name ?? 'Redaksi' }}</span>
                                <span>&bull;</span>
                                <time datetime="{{ $item->published_at?->toIso8601String() }}">
                                    {{ $item->published_at?->format('d M Y') }}
                                </time>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            @if($newsList->hasPages())
                <div class="pagination-wrap">
                    {{ $newsList->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
