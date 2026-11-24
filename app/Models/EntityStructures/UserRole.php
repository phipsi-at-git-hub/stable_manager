<?php
// app/Models/Identity/UserRole.php 

namespace App\Models\EntityStructures;

use App\Models\Concerns\HasAuditUsers;
use App\Models\Identity\User;
use App\Models\Organizations\Club;
use App\Models\Organizations\Stable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids; 
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * RoleUser
 */
class UserRole extends Pivot
{
    use HasAuditUsers;
    use HasUuids; 

    protected $table = 'user_roles';

    public $incrementing = false;

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'role_id',
        'club_id',
        'stable_id',
        'created_by',
        'updated_by',
    ];

    /**
     * user
     * 
     * Represents a role assignment to a user. 
     * The assignment can be global or scoped to a club or stable.
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

    /**
     * club
     * 
     * Club scope of this role assignment. 
     *
     * @return BelongsTo
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * stable
     * 
     * Stable scope of this role assignment. 
     *
     * @return BelongsTo
     */
    public function stable(): BelongsTo 
    {
        return $this->belongsTo(Stable::class);
    }
}
