<?php
// app/Models/Identity/RoleUser.php 

namespace App\Models\Identity;

use App\Models\Concerns\HasAuditUsers;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * RoleUser
 */
class RoleUser extends Pivot
{
    use HasAuditUsers;

    protected $table = 'role_user';

    public $incrementing = false;

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'role_id',
        'created_by',
        'updated_by',
    ];

    /**
     * user
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * role
     *
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(UserRole::class, 'role_id');
    }
}
