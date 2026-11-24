<?php
// app/Models/Organizations/Club.php

namespace App\Models\Organizations;

use App\Models\Concerns\HasAuditUsers;
use App\Models\Contact\Address;
use App\Models\Contact\Email;
use App\Models\Contact\PhoneNumber;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Club extends Model
{
    use HasUuids;
    use HasAuditUsers;

    protected $table = 'clubs';

    protected $guarded = [];

    public function stables(): HasMany
    {
        return $this->hasMany(Stable::class);
    }

    public function addresses(): BelongsToMany
    {
        return $this->belongsToMany(Address::class, 'club_addresses', 'club_id', 'address_id')
            ->using(ClubAddress::class)
            ->withPivot(['is_primary', 'created_by', 'updated_by'])
            ->withTimestamps();
    }

    public function emails(): BelongsToMany
    {
        return $this->belongsToMany(Email::class, 'club_emails', 'club_id', 'email_id')
            ->using(ClubEmail::class)
            ->withPivot(['is_primary', 'created_by', 'updated_by'])
            ->withTimestamps();
    }

    public function phoneNumbers(): BelongsToMany
    {
        return $this->belongsToMany(PhoneNumber::class, 'club_phone_numbers', 'club_id', 'phone_number_id')
            ->using(ClubPhoneNumber::class)
            ->withPivot(['is_primary', 'created_by', 'updated_by'])
            ->withTimestamps();
    }
}
