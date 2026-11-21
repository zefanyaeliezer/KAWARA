@extends('layouts.admin')

@section('title', 'Edit Berita — KAWARA Admin')

@section('content')
    <div style="max-width: 820px; margin: 0 auto;">
        <a href="{{ route('admin.dashboard') }}" class="back-link">&larr; Kembali ke Dashboard</a>

        <div class="panel">
            <div class="panel-header">
                <h1 class="section-title">Edit Berita</h1>
            </div>

            <div class="panel-body">
                <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @include('admin.news._form', ['news' => $news])

                    <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:24px;">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
