<?php

namespace App\Providers;

use App\Models\Report;
use App\Models\User;
use App\Policies\ReportPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Report::class => ReportPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('manageCategories', [UserPolicy::class, 'manageCategories']);
        Gate::define('manageUsers', [UserPolicy::class, 'manageUsers']);
        Gate::define('viewAnalytics', [UserPolicy::class, 'viewAnalytics']);
        Gate::define('exportData', [UserPolicy::class, 'exportData']);
    }
}
