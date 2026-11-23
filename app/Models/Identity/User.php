<?php
// app/Models/Identity/User.php 

namespace App\Models\Identity;

use App\Enums\Identity\UserStatus;
use App\Models\Concerns\HasAuditUsers;
use App\Models\People\Email;
use App\Models\People\Person;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne; 
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * User
 */
class User extends Authenticatable
{
    use HasFactory;
    use HasUuids;
    use Notifiable;
    use HasAuditUsers;

    protected $fillable = [
        'person_id',
        'username',
        'login_email_id',
        'password',
        'preferred_locale',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * casts
     *
     * @return array
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => UserStatus::class,
        ];
    }

    /**
     * person
     * 
     * Person associated with the user. 
     *
     * @return BelongsTo
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
    
    /**
     * loginEmail
     * 
     * Login email associated with the user. 
     *
     * @return BelongsTo
     */
    public function loginEmail(): BelongsTo
    {
        return $this->belongsTo(Email::class, 'login_email_id'); 
    }

    /**
     * statistics
     * 
     * Statistics associated with the user. 
     *
     * @return HasOne
     */
    public function statistics(): HasOne
    {
        return $this->hasOne(UserStatistic::class); 
    }

    /**
     * User role methods
     */

    /**
     * roles
     * 
     * Roles assigned to the user. 
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'role_user',
            'user_id',
            'role_id'
        )
            ->using(UserRole::class)
            ->withPivot([
                'created_by',
                'updated_by',
            ])
            ->withTimestamps();
    }

    /**
     * hasRole
     * 
     * Check whether the user has a specific role. 
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()
            ->where('name', $role)
            ->exists();
    }

    /**
     * hasAnyRole
     * 
     * Check whether the user has at least one of the specified roles. 
     *
     * @param array $roles
     * @return bool
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()
            ->whereIn('name', $roles)
            ->exists();
    }

    /**
     * isSuperAdmin
     * 
     * Check whether the user is a super administrator. 
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole(Role::SUPER_ADMIN);
    }

    /**
     * isAdmin
     * 
     * Check whether the user is an administrator. 
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasAnyRole([
            Role::SUPER_ADMIN,
            Role::ADMIN,
        ]);
    }

    /**
     * User status methods
     */

    /**
     * isActive
     * 
     * Check whether the user is active. 
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === UserStatus::ACTIVE;
    }

    /**
     * isInactive
     * 
     * Check whether the user is inactive. 
     *
     * @return bool
     */
    public function isInactive(): bool
    {
        return $this->status === UserStatus::INACTIVE;
    }

    /**
     * isBlocked
     * 
     * Check whether the user is blocked. 
     *
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this->status === UserStatus::BLOCKED;
    }

    /**
     * isBanned
     * 
     * Check whether the user is banned. 
     *
     * @return bool
     */
    public function isBanned(): bool
    {
        return $this->status === UserStatus::BANNED;
    }

    /**
     * isClosed
     * 
     * Check whether the user is closed. 
     *
     * @return bool
     */
    public function isClosed(): bool
    {
        return $this->status === UserStatus::CLOSED;
    }
}
