<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'pemerintah']);
    }

    public function update(User $user, Article $article): bool
    {
        return $article->author_id === $user->id || $user->hasRole('admin');
    }

    public function delete(User $user, Article $article): bool
    {
        return $article->author_id === $user->id || $user->hasRole('admin');
    }
}
