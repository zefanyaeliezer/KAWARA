@extends('layouts.admin')

@section('title', 'Dashboard Admin — KAWARA')

@section('content')
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Berita Dipublikasikan</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Berita Hari Ini</div>
            <div class="stat-value">{{ $stats['today'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Topik</div>
            <div class="stat-value">{{ $stats['categories'] }}</div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <div>
                <h1 class="section-title" style="margin-bottom: 2px;">Daftar Berita</h1>
                <span class="section-sub">Kelola seluruh berita yang tampil ke pengunjung (guest).</span>
            </div>
            <a href="{{ route('admin.news.create') }}" class="btn btn-primary">+ Buat Berita Baru</a>
        </div>

        @if($newsList->isEmpty())
            <div class="panel-body">
                <div class="empty-state" style="border: none; padding: 40px 16px;">
                    <h2>Belum ada berita yang diunggah</h2>
                    <p style="margin-bottom: 18px;">Mulai publikasikan berita pertama Anda sekarang.</p>
                    <a href="{{ route('admin.news.create') }}" class="btn btn-primary">+ Buat Berita Pertama</a>
                </div>
            </div>
        @else
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Judul Berita</th>
                            <th>Topik</th>
                            <th>Tanggal Publikasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($newsList as $item)
                            <tr>
                                <td>
                                    @if($item->thumbnail_url)
                                        <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}" class="thumb-preview">
                                    @else
                                        <div class="thumb-preview" style="display:flex;align-items:center;justify-content:center;font-size:0.7rem;color:#78716c;">
                                            No Img
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-weight: 600;">{{ $item->title }}</div>
                                    <div style="font-size: 0.8rem; color: var(--ink-soft);">/berita/{{ $item->slug }}</div>
                                </td>
                                <td>
                                    <span class="badge" style="margin-bottom: 0;">{{ $item->category }}</span>
                                </td>
                                <td>
                                    {{ $item->published_at?->format('d M Y, H:i') }} WIB
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <a href="{{ route('news.show', $item->slug) }}" class="btn btn-outline btn-sm" target="_blank">Lihat</a>
                                        <a href="{{ route('admin.news.edit', $item) }}" class="btn btn-outline btn-sm">Edit</a>
                                        <form
                                            action="{{ route('admin.news.destroy', $item) }}"
                                            method="POST"
                                            style="margin:0;"
                                            onsubmit="return confirm('Yakin ingin menghapus berita ini?');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger-outline btn-sm">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($newsList->hasPages())
                <div class="panel-body" style="padding-top: 12px;">
                    <div class="pagination-wrap" style="margin-top: 0;">
                        {{ $newsList->links() }}
                    </div>
                </div>
            @endif
        @endif
    </div>
@endsection
