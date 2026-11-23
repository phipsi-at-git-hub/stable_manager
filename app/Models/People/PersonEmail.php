<?php
// app/Models/People/PersonEmail.php 

namespace App\Models\People;

use App\Models\Concerns\HasAuditUsers;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * PersonEmail
 */
class PersonEmail extends Pivot
{
    use HasAuditUsers;

    protected $table = 'person_emails';

    public $incrementing = false;

    public $timestamps = true;

    protected $fillable = [
        'person_id',
        'email_id',
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
     * Person associated with this email assignment.
     *
     * @return BelongsTo
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * email
     * 
     * Email associated with this assignment.
     *
     * @return BelongsTo
     */
    public function email(): BelongsTo
    {
        return $this->belongsTo(Email::class);
    }
}
