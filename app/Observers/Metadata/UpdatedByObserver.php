<?php
// app/Observers/Metadata/UpdatedByObserver.php 

namespace App\Observers\Metadata;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * UpdatedByObserver
 */
class UpdatedByObserver
{
    /**
     * creating
     *
     * @param Model $model
     * @return void
     */
    public function creating(Model $model): void
    {
        $userId = Auth::id();

        if ($userId === null) {
            return;
        }

        $model->updated_by = $userId;
    }

    /**
     * updating
     *
     * @param Model $model
     * @return void
     */
    public function updating(Model $model): void
    {
        $userId = Auth::id();

        if ($userId === null) {
            return;
        }

        $model->updated_by = $userId;
    }
}
