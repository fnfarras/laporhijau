<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Event;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate sitemap.xml dinamis untuk semua halaman publik.
     */
    public function index(): Response
    {
        $articles = Article::published()
            ->latest('published_at')
            ->get(['slug', 'updated_at']);

        $events = Event::where('event_date', '>=', now())
            ->get(['id', 'updated_at']);

        $content = view('sitemap', compact('articles', 'events'))->render();

        return response($content, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
