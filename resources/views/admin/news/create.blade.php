@extends('layouts.admin')

@section('title', 'Buat Berita Baru — KAWARA Admin')

@section('content')
    <div style="max-width: 820px; margin: 0 auto;">
        <a href="{{ route('admin.dashboard') }}" class="back-link">&larr; Kembali ke Dashboard</a>

        <div class="panel">
            <div class="panel-header">
                <h1 class="section-title">Buat Berita Baru</h1>
            </div>

            <div class="panel-body">
                <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @include('admin.news._form')

                    <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:24px;">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">Batal</a>
                        <button type="submit" class="btn btn-primary">Publikasikan Berita</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
