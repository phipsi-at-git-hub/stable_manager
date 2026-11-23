<?php
// app/Models/Identity/UserRole.php 

namespace App\Models\Identity;

use App\Models\Concerns\HasAuditUsers;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * RoleUser
 */
class UserRole extends Pivot
{
    use HasAuditUsers;

    protected $table = 'user_roles';

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
     * User associated with this role assignment. 
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
     * Role associated with this assignment. 
     *
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        //return $this->belongsTo(Role::class, 'role_id');
        return $this->belongsTo(Role::class);
    }
}
