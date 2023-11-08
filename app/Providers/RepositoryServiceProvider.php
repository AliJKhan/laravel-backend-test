<?php

namespace App\Providers;

use App\Interfaces\UserAchievementRepositoryInterface;
use App\Repositories\UserAchievementRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserAchievementRepositoryInterface::class, UserAchievementRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
