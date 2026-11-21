<?php
// app/Observer/Audit/AuditLogObserver.php 

namespace App\Observers\Audit;

use App\Models\Audit\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogObserver
{
    /**
     * Attributes that must never be stored in audit logs.
     */
    private const EXCLUDED_ATTRIBUTES = [
        'password',
        'password_confirmation',
        'current_password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'recovery_codes',
        'access_token',
        'refresh_token',
        'client_secret',
        'private_key',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    /**
     * Handle the model created event.
     */
    public function created(Model $model): void
    {
        $newValues = $this->filterAttributes(
            $model->getAttributes()
        );

        if ($newValues === []) {
            return;
        }

        $this->writeLog(
            model: $model,
            event: 'created',
            newValues: $newValues,
        );
    }

    /**
     * Handle the model updated event.
     */
    public function updated(Model $model): void
    {
        $changes = $this->filterAttributes(
            $model->getChanges()
        );

        if ($changes === []) {
            return;
        }

        $oldValues = [];

        foreach (array_keys($changes) as $attribute) {
            $oldValues[$attribute] = $model->getRawOriginal($attribute);
        }

        $this->writeLog(
            model: $model,
            event: 'updated',
            oldValues: $oldValues,
            newValues: $changes,
        );
    }

    /**
     * Handle the model deleted event.
     */
    public function deleted(Model $model): void
    {
        $oldValues = $this->filterAttributes(
            $model->getRawOriginal()
        );

        if ($oldValues === []) {
            return;
        }

        $this->writeLog(
            model: $model,
            event: 'deleted',
            oldValues: $oldValues,
        );
    }

    /**
     * Remove excluded attributes.
     */
    private function filterAttributes(array $attributes): array
    {
        return array_diff_key(
            $attributes,
            array_flip(self::EXCLUDED_ATTRIBUTES)
        );
    }

    /**
     * Create an audit log entry.
     */
    private function writeLog(
        Model $model,
        string $event,
        array $oldValues = [],
        array $newValues = [],
    ): void {
        AuditLog::create([
            'user_id' => Auth::id(),
            'event' => $event,
            'auditable_type' => $model->getMorphClass(),
            'auditable_id' => $model->getKey(),
            'old_values' => $oldValues ?: null,
            'new_values' => $newValues ?: null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
