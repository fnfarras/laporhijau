<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminArticleController extends Controller
{
    public function index(Request $request): View
    {
        $query = Article::with('author')->latest();

        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->whereNotNull('published_at')->where('published_at', '<=', now());
            } else {
                $query->where(fn($q) => $q->whereNull('published_at')->orWhere('published_at', '>', now()));
            }
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $articles   = $query->paginate(15)->withQueryString();
        $categories = Article::CATEGORIES;

        return view('admin.articles.index', compact('articles', 'categories'));
    }

    public function create(): View
    {
        $categories = Article::CATEGORIES;
        return view('admin.articles.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'category' => 'required|string|in:' . implode(',', Article::CATEGORIES),
            'content'  => 'required|string|min:50',
            'publish'  => 'nullable|boolean',
        ], [
            'title.required'    => 'Judul artikel wajib diisi.',
            'category.required' => 'Kategori wajib dipilih.',
            'content.min'       => 'Konten minimal 50 karakter.',
        ]);

        $slug  = Str::slug($data['title']);
        $base  = $slug;
        $count = 0;
        while (Article::where('slug', $slug)->exists()) {
            $slug = $base . '-' . ++$count;
        }

        Article::create([
            'author_id'    => auth()->id(),
            'title'        => $data['title'],
            'slug'         => $slug,
            'category'     => $data['category'],
            'content'      => $data['content'],
            'published_at' => $request->boolean('publish') ? now() : null,
        ]);

        return redirect()->route('admin.articles.index')
            ->with('success', "Artikel \"{$data['title']}\" berhasil " . ($request->boolean('publish') ? 'dipublikasikan' : 'disimpan sebagai draft') . '.');
    }

    public function edit(Article $article): View
    {
        $categories = Article::CATEGORIES;
        return view('admin.articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'category' => 'required|string|in:' . implode(',', Article::CATEGORIES),
            'content'  => 'required|string|min:50',
            'publish'  => 'nullable|boolean',
        ]);

        // Update slug jika judul berubah
        if ($article->title !== $data['title']) {
            $slug  = Str::slug($data['title']);
            $base  = $slug;
            $count = 0;
            while (Article::where('slug', $slug)->where('id', '!=', $article->id)->exists()) {
                $slug = $base . '-' . ++$count;
            }
            $article->slug = $slug;
        }

        $article->title    = $data['title'];
        $article->category = $data['category'];
        $article->content  = $data['content'];

        // Toggle publish
        if ($request->boolean('publish') && !$article->published_at) {
            $article->published_at = now();
        } elseif (!$request->boolean('publish')) {
            $article->published_at = null;
        }

        $article->save();

        return redirect()->route('admin.articles.index')
            ->with('success', "Artikel \"{$article->title}\" berhasil diperbarui.");
    }

    public function destroy(Article $article): RedirectResponse
    {
        $title = $article->title;
        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', "Artikel \"{$title}\" berhasil dihapus.");
    }
}
