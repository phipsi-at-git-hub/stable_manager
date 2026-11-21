<?php
//app/Models/People/Address.php 

namespace App\Models\People;

use App\Models\Concerns\HasAuditUsers;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Address
 */
class Address extends Model
{
    use HasUuids;
    use HasAuditUsers;

    protected $fillable = [
        'person_id',
        'street',
        'postal_code',
        'city',
        'country',
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
