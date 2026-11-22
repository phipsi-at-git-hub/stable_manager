<?php

namespace App\Providers;

use App\Models\Identity\User;
use App\Models\Identity\UserRole;
use App\Models\People\Address;
use App\Models\People\Person;
use App\Models\People\PhoneNumber;
use App\Models\Settings\SystemSetting;
use App\Observers\Metadata\CreatedByObserver;
use App\Observers\Metadata\UpdatedByObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $modelsWithCreatedBy = [
            User::class, 
            UserRole::class, 
            Person::class, 
            Address::class, 
            PhoneNumber::class, 
        ]; 

        $modelsWithUpdatedBy = [
            ...$modelsWithCreatedBy, 
            SystemSetting::class, 
        ]; 

        foreach ($modelsWithCreatedBy as $model) {
            $model::observe(CreatedByObserver::class); 
        }

        foreach ($modelsWithUpdatedBy as $model) {
            $model::observe(UpdatedByObserver::class); 
        }
    }
}
