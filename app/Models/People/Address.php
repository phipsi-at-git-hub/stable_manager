<?php
//app/Models/People/Address.php 

namespace App\Models\People;

use App\Models\Concerns\HasAuditUsers;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Address
 */
class Address extends Model
{
    use HasUuids;
    use HasAuditUsers;

    protected $fillable = [
        'street',
        'postal_code',
        'city',
        'country',
    ];

    /**
     * person
     * 
     * Persons associated with this address. 
     *
     * @return BelongsToMany
     */
    public function person(): BelongsToMany
    {
        return $this->belongsToMany(
            Person::class, 
            'person_addresses', 
            'address_id', 
            'person_id'
        )
            ->using(PersonAddress::class)
            ->withPivot([
                'is_primary', 
                'created_by', 
                'updated_by'
            ])
            ->withTimestamps();
    }
}
