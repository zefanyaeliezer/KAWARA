<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        $newsList = News::query()
            ->withoutThumbnailData()
            ->with('author')
            ->latest('published_at')
            ->latest('id')
            ->paginate(12);

        $stats = [
            'total' => News::count(),
            'today' => News::whereDate('created_at', today())->count(),
            'categories' => count(News::CATEGORIES),
        ];

        return view('admin.dashboard', [
            'newsList' => $newsList,
            'stats' => $stats,
        ]);
    }

    public function create(): View
    {
        return view('admin.news.create', [
            'categories' => News::CATEGORIES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', Rule::in(News::CATEGORIES)],
            'excerpt' => ['nullable', 'string', 'max:400'],
            'content' => ['required', 'string'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'title.required' => 'Judul berita wajib diisi.',
            'category.required' => 'Topik wajib dipilih.',
            'category.in' => 'Topik yang dipilih tidak valid.',
            'content.required' => 'Isi berita wajib diisi.',
            'thumbnail.image' => 'File harus berupa gambar.',
            'thumbnail.max' => 'Ukuran gambar maksimal 2 MB.',
        ]);

        $thumbnailData = null;
        $thumbnailMime = null;

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $thumbnailData = base64_encode(file_get_contents($file->getRealPath()));
            $thumbnailMime = $file->getMimeType() ?: 'image/jpeg';
        }

        News::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'slug' => News::generateUniqueSlug($validated['title']),
            'category' => $validated['category'],
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => $validated['content'],
            'thumbnail' => null,
            'thumbnail_data' => $thumbnailData,
            'thumbnail_mime' => $thumbnailMime,
            'published_at' => now(),
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Berita berhasil dipublikasikan.');
    }

    public function edit(News $news): View
    {
        return view('admin.news.edit', [
            'news' => $news,
            'categories' => News::CATEGORIES,
        ]);
    }

    public function update(Request $request, News $news): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', Rule::in(News::CATEGORIES)],
            'excerpt' => ['nullable', 'string', 'max:400'],
            'content' => ['required', 'string'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_thumbnail' => ['nullable', 'boolean'],
        ], [
            'title.required' => 'Judul berita wajib diisi.',
            'category.required' => 'Topik wajib dipilih.',
            'category.in' => 'Topik yang dipilih tidak valid.',
            'content.required' => 'Isi berita wajib diisi.',
            'thumbnail.image' => 'File harus berupa gambar.',
            'thumbnail.max' => 'Ukuran gambar maksimal 2 MB.',
        ]);

        $thumbnailPath = $news->thumbnail;
        $thumbnailData = $news->thumbnail_data;
        $thumbnailMime = $news->thumbnail_mime;

        if ($request->boolean('remove_thumbnail')) {
            if ($thumbnailPath) {
                Storage::disk('public')->delete($thumbnailPath);
            }
            $thumbnailPath = null;
            $thumbnailData = null;
            $thumbnailMime = null;
        }

        if ($request->hasFile('thumbnail')) {
            if ($thumbnailPath) {
                Storage::disk('public')->delete($thumbnailPath);
                $thumbnailPath = null;
            }
            $file = $request->file('thumbnail');
            $thumbnailData = base64_encode(file_get_contents($file->getRealPath()));
            $thumbnailMime = $file->getMimeType() ?: 'image/jpeg';
        }

        $slug = $news->title !== $validated['title']
            ? News::generateUniqueSlug($validated['title'], $news->id)
            : $news->slug;

        $news->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'category' => $validated['category'],
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => $validated['content'],
            'thumbnail' => $thumbnailPath,
            'thumbnail_data' => $thumbnailData,
            'thumbnail_mime' => $thumbnailMime,
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(News $news): RedirectResponse
    {
        if ($news->thumbnail) {
            Storage::disk('public')->delete($news->thumbnail);
        }

        $news->delete();

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Berita berhasil dihapus.');
    }
}
