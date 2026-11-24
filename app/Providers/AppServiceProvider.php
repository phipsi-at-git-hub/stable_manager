<?php

namespace App\Providers;

use App\Models\Contact\Address;
use App\Models\Contact\Email;
use App\Models\Contact\PhoneNumber;
use App\Models\EntityStructures\PersonRole;
use App\Models\EntityStructures\Role;
use App\Models\EntityStructures\UserRole;
use App\Models\Identity\User;
use App\Models\Organizations\Club;
use App\Models\Organizations\ClubAddress;
use App\Models\Organizations\ClubEmail;
use App\Models\Organizations\ClubPhoneNumber;
use App\Models\Organizations\Stable;
use App\Models\Organizations\StableAddress;
use App\Models\Organizations\StableEmail;
use App\Models\Organizations\StablePhoneNumber;
use App\Models\People\Person;
use App\Models\People\PersonAddress;
use App\Models\People\PersonEmail;
use App\Models\People\PersonPhoneNumber;
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

            Person::class, 
            PersonAddress::class, 
            PersonEmail::class, 
            PersonPhoneNumber::class, 

            Address::class, 
            Email::class, 
            PhoneNumber::class, 

            Role::class, 
            UserRole::class, 
            PersonRole::class,

            Club::class, 
            ClubAddress::class, 
            ClubEmail::class, 
            ClubPhoneNumber::class, 

            Stable::class,
            StableAddress::class, 
            StableEmail::class, 
            StablePhoneNumber::class,
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
