<?php
// app/Models/Identity/UserRole.php 

namespace App\Models\Identity;

use App\Models\Concerns\HasAuditUsers;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserRole extends Model
{
    use HasUuids;
    use HasAuditUsers;

    /**
     * Available user roles
     */
    public const SUPER_ADMIN = 'super_admin';
    public const ADMIN = 'admin';
    public const USER = 'user';
    public const TRAINER = 'trainer';
    public const STUDENT = 'student';
    public const CLUB_MEMBER = 'club_member';
    public const CLUB_EMPLOYER = 'club_employer';

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * users
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'role_user',
            'role_id',
            'user_id'
        )
            ->using(RoleUser::class)
            ->withPivot([
                'created_by',
                'updated_by',
            ])
            ->withTimestamps();
    }
}
