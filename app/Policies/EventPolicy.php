<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    /**
     * Hanya relawan, pemerintah, dan admin yang bisa membuat event.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['relawan', 'pemerintah', 'admin']);
    }

    /**
     * Organizer, admin bisa mengupdate event.
     */
    public function update(User $user, Event $event): bool
    {
        return $event->organizer_id === $user->id || $user->hasRole('admin');
    }

    /**
     * Organizer, admin bisa menghapus event.
     */
    public function delete(User $user, Event $event): bool
    {
        return $event->organizer_id === $user->id || $user->hasRole('admin');
    }
}
