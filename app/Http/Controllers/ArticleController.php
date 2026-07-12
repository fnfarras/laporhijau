<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    /** Daftar artikel (publik) */
    public function index(Request $request): View
    {
        $query = Article::published()->with('author')->latest('published_at');

        if ($request->filled('kategori')) {
            $query->where('category', $request->kategori);
        }

        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        $articles   = $query->paginate(9)->withQueryString();
        $categories = Article::CATEGORIES;

        return view('artikel.index', compact('articles', 'categories'));
    }

    /** Detail artikel by slug (publik) */
    public function show(string $slug): View
    {
        $article = Article::published()->with('author')->where('slug', $slug)->firstOrFail();

        $related = Article::published()
            ->where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('artikel.show', compact('article', 'related'));
    }

    /** Form buat artikel (admin & pemerintah) */
    public function create(): View
    {
        Gate::authorize('create', Article::class);
        $categories = Article::CATEGORIES;
        return view('artikel.create', compact('categories'));
    }

    /**
     * Simpan artikel baru.
     * Otorisasi berlapis: Form Request authorize() + Gate::authorize() di sini.
     */
    public function store(StoreArticleRequest $request): RedirectResponse
    {
        Gate::authorize('create', Article::class);

        $slug  = Str::slug($request->title);
        $base  = $slug;
        $count = 0;

        // Pastikan slug unik
        while (Article::where('slug', $slug)->exists()) {
            $slug = $base . '-' . ++$count;
        }

        $article = Article::create([
            'author_id'    => auth()->id(),
            'title'        => $request->title,
            'slug'         => $slug,
            'category'     => $request->category,
            'content'      => $request->content,
            'published_at' => $request->boolean('publish') ? now() : null,
        ]);

        return redirect()->route('artikel.show', $article->slug)
            ->with('success', '✅ Artikel berhasil dipublikasikan!');
    }
}
