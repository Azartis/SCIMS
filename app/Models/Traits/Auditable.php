<?php

namespace App\Models\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    protected $auditableAttributes = [];

    public static function bootAuditable()
    {
        static::created(function ($model) {
            $model->logAudit('created', null, $model->getAuditableValues());
        });

        static::updated(function ($model) {
            $oldValues = $model->getOldAuditValues();
            $newValues = $model->getAuditableValues();

            // Only log if there are actual changes
            if ($oldValues !== $newValues) {
                $model->logAudit('updated', $oldValues, $newValues);
            }
        });

        static::deleted(function ($model) {
            $model->logAudit('deleted', $model->getAuditableValues(), null);
        });
    }

    protected function logAudit($event, $oldValues = null, $newValues = null)
    {
        AuditLog::create([
            'user_id' => Auth::id() ?? null,
            'auditable_type' => get_class($this),
            'auditable_id' => $this->id,
            'event' => $event,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'url' => request()->fullUrl() ?? null,
            'ip_address' => request()->ip() ?? null,
            'user_agent' => request()->userAgent() ?? null,
        ]);
    }

    protected function getAuditableValues()
    {
        $attributes = !empty($this->auditableAttributes) 
            ? $this->auditableAttributes 
            : array_keys($this->getAttributes());

        return collect($this->getAttributes())
            ->only($attributes)
            ->toArray();
    }

    protected function getOldAuditValues()
    {
        $attributes = !empty($this->auditableAttributes) 
            ? $this->auditableAttributes 
            : array_keys($this->getOriginal());

        return collect($this->getOriginal())
            ->only($attributes)
            ->toArray();
    }
}
