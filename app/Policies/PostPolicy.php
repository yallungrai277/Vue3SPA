<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use App\Traits\CheckPermission;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization, CheckPermission;

    public function before(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return $this->checkPermission($user, 'posts.manage', false);
    }

    public function create(User $user)
    {
        return $this->checkPermission($user, 'posts.create', false);
    }

    public function view(User $user, Post $post)
    {
        return $this->checkPermission($user, 'posts.manage', false) && $user->id === $post->user_id;
    }

    public function update(User $user, Post $post)
    {
        return $this->checkPermission($user, 'posts.update', false) && $user->id === $post->user_id;
    }


    public function delete(User $user, Post $post)
    {
        return $this->checkPermission($user, 'posts.delete', false) && $user->id === $post->user_id;
    }
}