<div class="form-group">
    <label for="title" class="form-label">Judul Berita <span style="color:var(--accent);">*</span></label>
    <input
        id="title"
        type="text"
        name="title"
        value="{{ old('title', $news->title ?? '') }}"
        class="form-control"
        placeholder="Tulis judul berita yang menarik..."
        required
    >
    @error('title')
        <div class="field-error">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="category" class="form-label">Topik <span style="color:var(--accent);">*</span></label>
    <select id="category" name="category" class="form-control" required>
        @foreach($categories as $category)
            <option
                value="{{ $category }}"
                {{ old('category', $news->category ?? $categories[0]) === $category ? 'selected' : '' }}
            >
                {{ $category }}
            </option>
        @endforeach
    </select>
    @error('category')
        <div class="field-error">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="excerpt" class="form-label">Ringkasan Singkat (Opsional)</label>
    <textarea
        id="excerpt"
        name="excerpt"
        rows="3"
        class="form-control"
        style="min-height: 84px;"
        placeholder="Ringkasan pendek yang muncul di kartu berita. Jika kosong, otomatis diambil dari isi berita."
    >{{ old('excerpt', $news->excerpt ?? '') }}</textarea>
    @error('excerpt')
        <div class="field-error">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="thumbnail" class="form-label">Gambar Utama / Thumbnail (Opsional)</label>
    <input
        id="thumbnail"
        type="file"
        name="thumbnail"
        accept="image/jpeg,image/png,image/webp"
        class="form-control"
    >
    <span class="form-hint">Format: JPG, PNG, atau WEBP. Maksimal 2 MB.</span>
    @error('thumbnail')
        <div class="field-error">{{ $message }}</div>
    @enderror

    @if(isset($news) && $news->thumbnail_url)
        <div class="current-thumb">
            <img src="{{ $news->thumbnail_url }}" alt="{{ $news->title }}">
            <div>
                <div style="font-size: 0.85rem; font-weight: 600; margin-bottom: 6px;">Thumbnail saat ini</div>
                <label class="checkbox-row">
                    <input type="checkbox" name="remove_thumbnail" value="1">
                    <span>Hapus thumbnail saat ini</span>
                </label>
            </div>
        </div>
    @endif
</div>

<div class="form-group">
    <label for="content" class="form-label">Isi Berita <span style="color:var(--accent);">*</span></label>
    <textarea
        id="content"
        name="content"
        rows="12"
        class="form-control"
        style="min-height: 260px;"
        placeholder="Tulis isi berita lengkap di sini..."
        required
    >{{ old('content', $news->content ?? '') }}</textarea>
    @error('content')
        <div class="field-error">{{ $message }}</div>
    @enderror
</div>
