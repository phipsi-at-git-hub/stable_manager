<?php

namespace App\Models\Organizations;

use App\Models\Concerns\HasAuditUsers;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ClubAddress extends Pivot
{
    use HasAuditUsers;

    protected $table = 'club_addresses';

    protected $guarded = [];

    public $incrementing = false;

    public $timestamps = true;

    protected $casts = [
        'is_primary' => 'boolean',
    ];
}
