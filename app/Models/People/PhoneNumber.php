<?php
// app/Models/People/PhoneNumber.php 

namespace App\Models\People;

use App\Models\Concerns\HasAuditUsers;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * PhoneNumber
 */
class PhoneNumber extends Model
{
    use HasUuids;
    use HasAuditUsers;

    protected $fillable = [
        'person_id',
        'phone_number',
    ];

    /**
     * person
     *
     * @return BelongsTo
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
