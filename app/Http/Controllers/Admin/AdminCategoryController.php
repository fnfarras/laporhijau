<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminCategoryController extends Controller
{
    public function index(): View
    {
        $categories = ReportCategory::withCount('reports')->orderBy('name')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:report_categories,name',
            'icon' => 'required|string|max:10',
        ]);

        ReportCategory::create($data);
        return back()->with('success', "Kategori \"{$data['name']}\" berhasil ditambahkan.");
    }

    public function update(Request $request, ReportCategory $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:report_categories,name,' . $category->id,
            'icon' => 'required|string|max:10',
        ]);

        $category->update($data);
        return back()->with('success', "Kategori \"{$category->name}\" berhasil diperbarui.");
    }

    public function destroy(ReportCategory $category): RedirectResponse
    {
        if ($category->reports()->count() > 0) {
            return back()->with('error', "Kategori \"{$category->name}\" tidak dapat dihapus karena masih digunakan oleh {$category->reports()->count()} laporan.");
        }

        $name = $category->name;
        $category->delete();
        return back()->with('success', "Kategori \"{$name}\" berhasil dihapus.");
    }
}
