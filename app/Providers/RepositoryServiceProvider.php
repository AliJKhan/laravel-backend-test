<?php

namespace App\Providers;

use App\Interfaces\UserAchievementRepositoryInterface;
use App\Interfaces\UserBadgeRepositoryInterface;
use App\Repositories\UserAchievementRepository;
use App\Repositories\UserBadgeRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserAchievementRepositoryInterface::class, UserAchievementRepository::class);
        $this->app->bind(UserBadgeRepositoryInterface::class, UserBadgeRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
