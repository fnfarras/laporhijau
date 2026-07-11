<?php

namespace App\Http\Controllers;

use App\Events\EventRsvpRegistered;
use App\Http\Requests\StoreEventRequest;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\PointLog;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Daftar semua event (publik).
     */
    public function index(Request $request): View
    {
        $filter = $request->get('filter', 'upcoming');

        $query = Event::with(['organizer', 'activeParticipants'])
            ->withCount(['activeParticipants']);

        if ($filter === 'past') {
            $query->where('event_date', '<', now())->orderByDesc('event_date');
        } else {
            $query->where('event_date', '>=', now())->orderBy('event_date');
        }

        $events = $query->get();

        return view('komunitas.event.index', compact('events', 'filter'));
    }

    /**
     * Detail event (publik).
     */
    public function show(Event $event): View
    {
        $event->load(['organizer', 'activeParticipants.user', 'report']);

        $userParticipant = null;
        if (auth()->check()) {
            $userParticipant = $event->participants()
                ->where('user_id', auth()->id())
                ->first();
        }

        return view('komunitas.event.show', compact('event', 'userParticipant'));
    }

    /**
     * Form buat event (relawan & pemerintah).
     */
    public function create(): View
    {
        $this->authorize('create', Event::class);

        $resolvedReports = Report::where('status', 'resolved')
            ->orderByDesc('created_at')
            ->get(['id', 'title']);

        $categories = Event::CATEGORIES;

        return view('komunitas.event.create', compact('resolvedReports', 'categories'));
    }

    /**
     * Simpan event baru (relawan & pemerintah).
     */
    public function store(StoreEventRequest $request): RedirectResponse
    {
        $event = Event::create([
            ...$request->validated(),
            'organizer_id' => auth()->id(),
        ]);

        return redirect()->route('event.show', $event)
            ->with('success', '🎉 Event berhasil dibuat! Ajak teman untuk ikut serta.');
    }

    /**
     * RSVP atau batalkan RSVP event.
     * Penambahan poin (+15) dilakukan via EventRsvpRegistered Event & Listener,
     * bukan hardcode di controller — sesuai konvensi proyek.
     */
    public function rsvp(Event $event): RedirectResponse
    {
        $user = auth()->user();

        // Cari partisipan existing (termasuk yang cancelled)
        $participant = EventParticipant::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        $registered = DB::transaction(function () use ($event, $user, $participant) {
            if ($participant && $participant->status === 'registered') {
                // ── Batalkan RSVP ──────────────────────────────────────
                $participant->update(['status' => 'cancelled']);

                // Kurangi -15 poin secara langsung (cancel tidak perlu Event)
                $user->decrement('points', 15);
                PointLog::create([
                    'user_id'      => $user->id,
                    'points'       => -15,
                    'reason'       => 'Batalkan RSVP event: ' . $event->title,
                    'reference_id' => $event->id,
                ]);

                return false;
            }

            // ── RSVP Baru ─────────────────────────────────────────
            if ($event->isFull()) {
                return null; // Penuh
            }

            if ($participant) {
                // Re-register (sebelumnya cancelled)
                $participant->update(['status' => 'registered']);
            } else {
                // Baru pertama RSVP
                EventParticipant::create([
                    'event_id' => $event->id,
                    'user_id'  => $user->id,
                    'status'   => 'registered',
                ]);
            }

            return true;
        });

        if ($registered === null) {
            return back()->with('error', 'Event ini sudah penuh.');
        }

        if ($registered === true) {
            // Fire Event → AwardEventPoints Listener akan memberi +15 poin
            EventRsvpRegistered::dispatch($event, $user);
            return back()->with('success', '✅ Berhasil RSVP! Kamu mendapat +15 poin. Sampai jumpa di event!');
        }

        return back()->with('success', '❌ RSVP dibatalkan. Poin -15 dikurangi.');
    }
}
