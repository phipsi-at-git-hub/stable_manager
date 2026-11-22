<?php
// app/Observers/MetaData/CreatedByObserver.php 

namespace App\Observers\Metadata;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * CreatedByObserver
 */
class CreatedByObserver
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

        $model->created_by = $userId;
    }
}
