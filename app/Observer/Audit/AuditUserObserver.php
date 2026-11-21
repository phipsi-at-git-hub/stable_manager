<?php
// app/Observer/Audit/AuditUserObserver.php 

namespace App\Observers\Audit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditUserObserver
{
    public function creating(Model $model): void
    {
        $userId = Auth::id();

        if ($userId === null) {
            return;
        }

        $model->setAttribute('created_by', $userId);
        $model->setAttribute('updated_by', $userId);
    }

    public function updating(Model $model): void
    {
        $userId = Auth::id();

        if ($userId === null) {
            return;
        }

        $model->setAttribute('updated_by', $userId);
    }
}
