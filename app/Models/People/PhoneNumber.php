<?php
// app/Models/People/PhoneNumber.php 

namespace App\Models\People;

use App\Models\Concerns\HasAuditUsers;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * PhoneNumber
 */
class PhoneNumber extends Model
{
    use HasUuids;
    use HasAuditUsers;

    protected $fillable = [
        'phone_number',
    ];

    /**
     * person
     * 
     * Persons associated with this phone number. 
     *
     * @return BelongsToMany
     */
    public function person(): BelongsToMany
    {
        return $this->belongsToMany(
            Person::class, 
            'person_phone_number', 
            'phone_number_id', 
            'person_id'
        )
            ->using(PersonPhoneNumber::class)
            ->withPivot([
                'is_primary', 
                'created_by', 
                'updated_by'
            ])
            ->withTimestamps();
    }
}
