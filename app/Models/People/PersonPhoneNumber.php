<?php
// app/Models/People/PersonPhoneNumber.php

namespace App\Models\People;

use App\Models\Concerns\HasAuditUsers;
use App\Models\Contact\PhoneNumber;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * PersonPhoneNumber
 */
class PersonPhoneNumber extends Pivot
{
    use HasAuditUsers;

    protected $table = 'person_phone_numbers';

    public $incrementing = false;

    public $timestamps = true;

    protected $fillable = [
        'person_id',
        'phone_number_id',
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
     * Person associated with this phone number assignment.
     *
     * @return BelongsTo
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * phoneNumber
     * 
     * Phone number associated with this assignment.
     *
     * @return BelongsTo
     */
    public function phoneNumber(): BelongsTo
    {
        return $this->belongsTo(PhoneNumber::class);
    }
}
