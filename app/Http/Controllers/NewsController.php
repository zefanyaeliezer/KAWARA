<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $selectedCategory = trim((string) $request->query('category', ''));

        $categories = News::CATEGORIES;

        if ($selectedCategory !== '' && ! in_array($selectedCategory, $categories, true)) {
            $selectedCategory = '';
        }

        $query = News::query()
            ->with('author')
            ->published()
            ->when($search !== '', function ($builder) use ($search) {
                $builder->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                });
            })
            ->when($selectedCategory !== '', fn ($builder) => $builder->where('category', $selectedCategory))
            ->latest('published_at')
            ->latest('id');

        $featuredNews = null;
        if ($search === '' && $selectedCategory === '' && (int) $request->query('page', 1) === 1) {
            $featuredNews = (clone $query)->first();
            if ($featuredNews) {
                $query->whereKeyNot($featuredNews->id);
            }
        }

        $newsList = $query->paginate(9)->withQueryString();

        return view('news.index', [
            'featuredNews' => $featuredNews,
            'newsList' => $newsList,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'search' => $search,
        ]);
    }

    public function show(News $news): View
    {
        abort_if(is_null($news->published_at) || $news->published_at->isFuture(), 404);

        $news->load('author');

        $relatedNews = News::query()
            ->published()
            ->whereKeyNot($news->id)
            ->latest('published_at')
            ->latest('id')
            ->limit(4)
            ->get();

        return view('news.show', [
            'news' => $news,
            'relatedNews' => $relatedNews,
        ]);
    }
}
