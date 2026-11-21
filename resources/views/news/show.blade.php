@extends('layouts.app')

@section('title', $news->title . ' — KAWARA')

@section('content')
    <div class="container-narrow">
        <a href="{{ route('news.index') }}" class="back-link">&larr; Kembali ke Beranda</a>

        <article class="article-shell">
            <span class="badge">{{ $news->category }}</span>
            <h1 class="article-title">{{ $news->title }}</h1>

            @if(filled($news->excerpt))
                <p class="article-lead">{{ $news->excerpt }}</p>
            @endif

            <div class="meta-line">
                <span>Oleh <strong>{{ $news->author?->name ?? 'Redaksi' }}</strong></span>
                <span>&bull;</span>
                <time datetime="{{ $news->published_at?->toIso8601String() }}">
                    {{ $news->published_at?->format('d M Y, H:i') }} WIB
                </time>
            </div>

            @if($news->thumbnail_url)
                <figure class="article-hero">
                    <img src="{{ $news->thumbnail_url }}" alt="{{ $news->title }}">
                </figure>
            @endif

            <div class="article-content">{{ $news->content }}</div>
        </article>

        @if($relatedNews->isNotEmpty())
            <section class="related-section">
                <div class="section-head">
                    <h2 class="section-title">Berita Lainnya</h2>
                </div>
                <div class="related-grid">
                    @foreach($relatedNews as $item)
                        <a href="{{ route('news.show', $item->slug) }}" class="related-card">
                            <span class="badge" style="margin-bottom: 6px;">{{ $item->category }}</span>
                            <h4>{{ $item->title }}</h4>
                            <div class="meta-line">
                                <time datetime="{{ $item->published_at?->toIso8601String() }}">
                                    {{ $item->published_at?->format('d M Y') }}
                                </time>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
