<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminRewardController extends Controller
{
    public function index(): View
    {
        $rewards = Reward::withCount('redemptions')->latest()->paginate(15);
        return view('admin.rewards.index', compact('rewards'));
    }

    public function create(): View
    {
        return view('admin.rewards.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'            => 'required|string|max:100',
            'description'     => 'required|string|max:500',
            'points_required' => 'required|integer|min:1',
            'type'            => 'required|in:certificate,merchandise,voucher',
            'icon'            => 'nullable|string|max:10',
            'is_active'       => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        Reward::create($data);

        return redirect()->route('admin.rewards.index')
            ->with('success', "Hadiah \"{$data['name']}\" berhasil ditambahkan.");
    }

    public function edit(Reward $reward): View
    {
        return view('admin.rewards.edit', compact('reward'));
    }

    public function update(Request $request, Reward $reward): RedirectResponse
    {
        $data = $request->validate([
            'name'            => 'required|string|max:100',
            'description'     => 'required|string|max:500',
            'points_required' => 'required|integer|min:1',
            'type'            => 'required|in:certificate,merchandise,voucher',
            'icon'            => 'nullable|string|max:10',
            'is_active'       => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $reward->update($data);

        return redirect()->route('admin.rewards.index')
            ->with('success', "Hadiah \"{$reward->name}\" berhasil diperbarui.");
    }

    public function destroy(Reward $reward): RedirectResponse
    {
        $name = $reward->name;
        $reward->delete();

        return redirect()->route('admin.rewards.index')
            ->with('success', "Hadiah \"{$name}\" berhasil dihapus.");
    }
}
