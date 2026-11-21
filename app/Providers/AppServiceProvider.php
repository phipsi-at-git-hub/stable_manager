<?php

namespace App\Providers;

use App\Models\Audit\AuditLog; 
use App\Models\Identity\User; 
use App\Models\Identity\UserRole; 
use App\Models\People\Person; 
use App\Models\People\Address;
use App\Models\People\PhoneNumber; 
use App\Models\Settings\SystemSetting; 

use App\Observers\Audit\AuditLogObserver; 
use App\Observers\Audit\AuditUserObserver; 
use App\Observers\Audit\SystemSettingObserver; 

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
        $auditUserModels = [
            User::class, 
            UserRole::class, 
            Person::class, 
            Address::class, 
            PhoneNumber::class, 
        ]; 

        foreach ($auditUserModels as $model) {
            $model::observe(AuditUserObserver::class); 
            $model::observe(AuditLogObserver::class); 
        }

        SystemSetting::observe(SystemSettingObserver::class); 
        SystemSetting::observe(AuditLogObserver::class); 
    }
}
