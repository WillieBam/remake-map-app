<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
Use App\Models\Report;
use App\Models\Message;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Message::class => \App\Policies\MessagePolicy::class,
        \App\Models\Report::class => \App\Policies\ReportPolicy::class,
        \App\Models\News::class => \App\Policies\NewsPolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('report-message', function(User $user, Message $message) {
            if ($user->role_id < 3)
                return false; // Admins cannot report messages

            $found = Report::where([['user_id', '=', $user->user_id], ['message_id', '=', $message->message_id]])->first();

            if ($found)
            {
                return false;
            }

            return true;
        });

        Gate::define('delete-message', function(User $user, Message $message) {
            if ($user->role_id === 1) {
                return true;
            } elseif ($user->role_id === 2) {
                return $user->country->continent->continent_id === $message->country->continent->continent_id;
            } else {
                return false;
            }
        });

        Gate::define('admin', function(User $user) {
            return $user->role_id == 1 || $user->role_id == 2;
        });
    }
}
