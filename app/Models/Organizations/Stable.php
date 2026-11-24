<?php

namespace App\Models\Organizations;

use App\Models\Concerns\HasAuditUsers;
use App\Models\Contact\Address;
use App\Models\Contact\Email;
use App\Models\Contact\PhoneNumber;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Stable extends Model
{
    use HasUuids;
    use HasAuditUsers;

    protected $table = 'stables';

    protected $guarded = [];

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function addresses(): BelongsToMany
    {
        return $this->belongsToMany(Address::class, 'stable_addresses', 'stable_id', 'address_id')
            ->using(StableAddress::class)
            ->withPivot(['is_primary', 'created_by', 'updated_by'])
            ->withTimestamps();
    }

    public function emails(): BelongsToMany
    {
        return $this->belongsToMany(Email::class, 'stable_emails', 'stable_id', 'email_id')
            ->using(StableEmail::class)
            ->withPivot(['is_primary', 'created_by', 'updated_by'])
            ->withTimestamps();
    }

    public function phoneNumbers(): BelongsToMany
    {
        return $this->belongsToMany(PhoneNumber::class, 'stable_phone_numbers', 'stable_id', 'phone_number_id')
            ->using(StablePhoneNumber::class)
            ->withPivot(['is_primary', 'created_by', 'updated_by'])
            ->withTimestamps();
    }
}
