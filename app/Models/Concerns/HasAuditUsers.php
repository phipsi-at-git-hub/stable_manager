<?php
// app/Models/Concerns/HasAuditUsers.php 

namespace App\Models\Concerns;

use App\Models\Identity\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * HasAuditUsers
 */
trait HasAuditUsers
{
    /**
     * createdBy
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * updatedBy
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
