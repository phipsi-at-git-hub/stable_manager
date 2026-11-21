<?php
// app/Models/Settings/SystemSettings.php 

namespace App\Models\Settings;

use App\Models\Identity\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * SystemSetting
 */
class SystemSetting extends Model
{
    use HasUuids;

    protected $fillable = [
        'key',
        'value',
        'updated_by',
    ];

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
