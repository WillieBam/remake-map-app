<?php

namespace App\Policies;

use App\Models\User;
use App\Models\News;
use App\Models\Country;

class NewsPolicy

    
{
    
    /**
     * Determine if the given news can be updated by the user.
     */
    public function update(User $user, News $news)
    {
        return $user->user_id === $news->user_id;
    }

    /**
     * Determine if the given news can be deleted by the user.
     */
    public function delete(User $user, News $news)
    {
        return $user->user_id === $news->user_id;
    }
    
    public function view(User $user, News $news){
        if ($user->role_id == 1) {
            return true; // Global Admin can view all news
        } elseif ($user->role_id == 2) {
            // Continent Admin can view news from countries in their continent
            return $user->country->continent_id === $news->country->continent_id;
        } else {
            // Regular users can only view their own news
            return $user->user_id === $news->user_id;
        }
    }

    public function viewAny(User $user){
     
        return true;
        
    }
}
