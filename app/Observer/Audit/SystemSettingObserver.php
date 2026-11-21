<?php
// app/Observer/Audit/SystemSettingObserver.php 

namespace App\Observers\Audit;

use App\Models\Settings\SystemSetting;
use Illuminate\Support\Facades\Auth;

class SystemSettingObserver
{
    public function creating(SystemSetting $setting): void
    {
        $this->setUpdatedBy($setting);
    }

    public function updating(SystemSetting $setting): void
    {
        $this->setUpdatedBy($setting);
    }

    private function setUpdatedBy(SystemSetting $setting): void
    {
        $userId = Auth::id();

        if ($userId === null) {
            return;
        }

        $setting->updated_by = $userId;
    }
}
