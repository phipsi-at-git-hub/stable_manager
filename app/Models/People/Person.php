<?php
// app/Models/People/Person.php 

namespace App\Models\People;

use App\Models\Concerns\HasAuditUsers;
use App\Models\Contact\Address;
use App\Models\Contact\Email;
use App\Models\Contact\PhoneNumber;
use App\Models\EntityStructures\PersonRole;
use App\Models\EntityStructures\Role;
use App\Models\Identity\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Person
 */
class Person extends Model
{
    use HasUuids;
    use HasAuditUsers;

    protected $fillable = [
        'first_name',
        'last_name',
        'title',
        'birthdate',
        'gender',
    ];

    /**
     * casts
     *
     * @return array
     */
    protected function casts(): array
    {
        return [
            'birthdate' => 'date',
        ];
    }

    /**
     * user
     * 
     * User associated with this person.
     *
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    /**
     * addresses
     * 
     * Addresses associated with this person. 
     *
     * @return BelongsToMany
     */
    public function addresses(): BelongsToMany
    {
        return $this->belongsToMany(
            Address::class, 
            'person_addresses', 
            'person_id', 
            'address_id'
        )
            ->using(PersonAddress::class)
            ->withPivot([
                'is_primary', 
                'created_by', 
                'updated_by'
            ])
            ->withTimestamps(); 
    }

    /**
     * phoneNumbers
     * 
     * Phone numbers associated with this person. 
     *
     * @return BelongsToMany
     */
    public function phoneNumbers(): BelongsToMany
    {
        return $this->belongsToMany(
            PhoneNumber::class, 
            'person_phone_numbers', 
            'person_id', 
            'phone_number_id'
        )
            ->using(PersonPhoneNumber::class)
            ->withPivot([
                'is_primary', 
                'created_by', 
                'updated_by'
            ])
            ->withTimestamps();
    }

    /**
     * emails
     * 
     * Email addresses associated with this person. 
     *
     * @return BelongsToMany
     */
    public function emails(): BelongsToMany
    {
        return $this->belongsToMany(
            Email::class, 
            'person_emails', 
            'person_id', 
            'email_id'
        )
            ->using(PersonEmail::class)
            ->withPivot([
                'is_primary', 
                'created_by', 
                'updated_by'
            ])
            ->withTimestamps(); 
    }

    public function roles() : BelongsToMany {
        return $this->belongsToMany(
            Role::class, 
            'person_roles', 
            'person_id', 
            'role_id'
        )
            ->using(PersonRole::class)
            ->withPivot([
                'id', 
                'club_id', 
                'stable_id', 
                'created_by', 
                'updated_by',
            ])
            ->withTimestamps(); 
    }
}
