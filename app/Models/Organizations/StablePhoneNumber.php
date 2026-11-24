<?php

namespace App\Models\Organizations;

use App\Models\Concerns\HasAuditUsers;
use Illuminate\Database\Eloquent\Relations\Pivot;

class StablePhoneNumber extends Pivot
{
    use HasAuditUsers;

    protected $table = 'stable_phone_numbers';

    protected $guarded = [];

    public $incrementing = false;

    public $timestamps = true;

    protected $casts = [
        'is_primary' => 'boolean',
    ];
}
