<?php
// app/Models/People/Email.php

namespace App\Models\People;

use App\Models\Concerns\HasAuditUsers;
use App\Models\Identity\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Email
 */
class Email extends Model
{
    use HasUuids;
    use HasAuditUsers;

    protected $fillable = [
        'email',
    ];

    /**
     * persons
     *
     * Persons associated with this email address.
     * 
     * @return BelongsToMany
     */
    public function persons(): BelongsToMany
    {
        return $this->belongsToMany(
            Person::class,
            'person_emails',
            'email_id',
            'person_id'
        )
            ->using(PersonEmail::class)
            ->withPivot([
                'is_primary', 
                'created_by', 
                'updated_by'
            ])
            ->withTimestamps();
    }

    /**
     * loginUser
     *
     * User using this email address for login.
     * 
     * @return HasOne
     */
    public function loginUser(): HasOne
    {
        return $this->hasOne(User::class, 'login_email_id');
    }
}
