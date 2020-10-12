<?php

namespace App\Policies;

use App\Models\User as ModelUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function update(ModelUser $currentUser, ModelUser $user)
    {
        return $currentUser->id === $user->id;
    }
}