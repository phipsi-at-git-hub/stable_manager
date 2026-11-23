<?php
// app/Models/Identity/UserStatistics.php 

namespace App\Models\Identity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * UserStatistic
 */
class UserStatistic extends Model
{
    protected $table = 'user_statistics';

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'successful_login_count',
        'first_login_at',
        'last_login_at',
    ];

    /**
     * casts
     *
     * @return array
     */
    protected function casts(): array
    {
        return [
            'successful_login_count' => 'integer',
            'first_login_at' => 'immutable_datetime',
            'last_login_at' => 'immutable_datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * user
     * 
     * User associated with these statistics.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
