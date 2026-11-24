<?php
// app/Models/Identity/PersonRole.php 

namespace App\Models\EntityStructures;

use App\Models\Concerns\HasAuditUsers;
use App\Models\Organizations\Club;
use App\Models\Organizations\Stable;
use App\Models\People\Person;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * PersonRole
 * 
 * Represents a role assignment to a person. 
 * The assignment must be scoped to either a club or a stable. 
 */
class PersonRole extends Pivot
{
    use HasAuditUsers;
    use HasUuids; 

    protected $table = 'person_roles';

    public $incrementing = false;

    public $timestamps = true;

    protected $fillable = [
        'person_id',
        'role_id',
        'club_id', 
        'stable_id',
        'created_by',
        'updated_by',
    ];

    /**
     * person
     * 
     * Person associated with this role assignment. 
     *
     * @return BelongsTo
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
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
