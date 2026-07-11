<?php

namespace App\Listeners;

use App\Events\EventRsvpRegistered;

class AwardEventPoints
{
    /**
     * +15 poin diberikan saat user berhasil RSVP ke sebuah event komunitas.
     * Synchronous agar poin langsung terlihat di UI.
     */
    public function handle(EventRsvpRegistered $event): void
    {
        $user      = $event->user;
        $eventModel = $event->event;

        // Tambah +15 poin ke user
        $user->increment('points', 15);

        // Catat ke point_logs
        $user->pointLogs()->create([
            'points'       => 15,
            'reason'       => 'RSVP event: ' . $eventModel->title,
            'reference_id' => $eventModel->id,
        ]);
    }
}
