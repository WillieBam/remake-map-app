<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    
    public function isGlobalAdmin(User $authUser)
    {
        return $authUser->role->name === "Global Admin" && !$authUser->is_banned;
    }

    
    public function isAdmin(User $authUser)
    {
        return ($authUser->role->name === "Continent Admin"||$authUser->role->name === "Global Admin")&& !$authUser->is_banned;
    }
    public function isUserLogIn(User $authUser,User $user){
        return $authUser -> user_id === $user -> user_id && !$authUser->is_banned;
    }
    public function adminViewUser(User $authUser,User $user){
        return $this->isAdmin($authUser) && $this->isUserLogIn($authUser,$user);
    }
}
