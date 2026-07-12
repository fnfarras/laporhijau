<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminLaporanController extends Controller
{
    public function index(Request $request): View
    {
        $query = Report::with(['category', 'user'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(fn($q) => $q
                ->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('address', 'like', '%' . $request->search . '%')
            );
        }

        $reports    = $query->paginate(20)->withQueryString();
        $categories = cache()->remember('laporhijau_categories', 3600, fn() => ReportCategory::orderBy('name')->get());

        $counts = [
            'total'       => Report::count(),
            'pending'     => Report::where('status', 'pending')->count(),
            'verified'    => Report::where('status', 'verified')->count(),
            'in_progress' => Report::where('status', 'in_progress')->count(),
            'resolved'    => Report::where('status', 'resolved')->count(),
            'rejected'    => Report::where('status', 'rejected')->count(),
        ];

        return view('admin.laporan.index', compact('reports', 'categories', 'counts'));
    }

    public function destroy(Report $report): RedirectResponse
    {
        $title = $report->title;
        $report->forceDelete();

        return redirect()->route('admin.laporan.index')
            ->with('success', "Laporan \"{$title}\" berhasil dihapus permanen.");
    }
}
