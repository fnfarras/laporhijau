<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminEventController extends Controller
{
    public function index(): View
    {
        $events = Event::with(['organizer'])
            ->withCount('activeParticipants')
            ->latest()
            ->paginate(15);

        return view('admin.events.index', compact('events'));
    }

    public function create(): View
    {
        $resolvedReports = Report::where('status', 'resolved')
            ->orderByDesc('created_at')
            ->get(['id', 'title']);

        $categories = Event::CATEGORIES;
        return view('admin.events.create', compact('resolvedReports', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string|min:20',
            'location'         => 'required|string|max:255',
            'category'         => 'required|string|in:' . implode(',', Event::CATEGORIES),
            'event_date'       => 'required|date|after:now',
            'max_participants' => 'nullable|integer|min:1',
            'report_id'        => 'nullable|exists:reports,id',
        ], [
            'event_date.after' => 'Tanggal event harus di masa depan.',
        ]);

        $data['organizer_id'] = auth()->id();

        Event::create($data);

        return redirect()->route('admin.events.index')
            ->with('success', "Event \"{$data['title']}\" berhasil dibuat.");
    }

    public function edit(Event $event): View
    {
        $resolvedReports = Report::where('status', 'resolved')
            ->orderByDesc('created_at')
            ->get(['id', 'title']);

        $categories = Event::CATEGORIES;
        return view('admin.events.edit', compact('event', 'resolvedReports', 'categories'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string|min:20',
            'location'         => 'required|string|max:255',
            'category'         => 'required|string|in:' . implode(',', Event::CATEGORIES),
            'event_date'       => 'required|date',
            'max_participants' => 'nullable|integer|min:1',
            'report_id'        => 'nullable|exists:reports,id',
        ]);

        $event->update($data);

        return redirect()->route('admin.events.index')
            ->with('success', "Event \"{$event->title}\" berhasil diperbarui.");
    }

    public function destroy(Event $event): RedirectResponse
    {
        $title = $event->title;
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', "Event \"{$title}\" berhasil dihapus.");
    }
}
