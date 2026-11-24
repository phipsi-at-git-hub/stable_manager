<?php
// app/Models/People/PersonAddress.php 

namespace App\Models\People;

use App\Models\Concerns\HasAuditUsers;
use App\Models\Contact\Address;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * PersonAddress
 */
class PersonAddress extends Pivot
{
    use HasAuditUsers;

    protected $table = 'person_addresses';

    public $incrementing = false;

    public $timestamps = true;

    protected $fillable = [
        'person_id',
        'address_id',
        'is_primary',
        'created_by',
        'updated_by',
    ];

    /**
     * casts
     *
     * @return array
     */
    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

    /**
     * person
     * 
     * Person associated with this address assignment.
     *
     * @return BelongsTo
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * address
     * 
     * Address associated with this assignment.
     *
     * @return BelongsTo
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}
