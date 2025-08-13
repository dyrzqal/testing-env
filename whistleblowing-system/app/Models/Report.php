<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Report extends Model
{
    protected $fillable = [
        'reference_number',
        'category_id',
        'title',
        'description',
        'incident_location',
        'incident_date',
        'incident_time',
        'persons_involved',
        'evidence_description',
        'urgency_level',
        'status',
        'resolution_details',
        'is_anonymous',
        'reporter_name',
        'reporter_email',
        'reporter_phone',
        'reporter_department',
        'reporter_contact_preference',
        'metadata',
        'submitted_at',
        'reviewed_at',
        'resolved_at',
        'assigned_to_user_id'
    ];

    protected $casts = [
        'persons_involved' => 'array',
        'metadata' => 'array',
        'incident_date' => 'date',
        'incident_time' => 'datetime:H:i',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'resolved_at' => 'datetime',
        'is_anonymous' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($report) {
            if (empty($report->reference_number)) {
                $report->reference_number = 'WB-' . strtoupper(Str::random(8));
            }
            if (empty($report->submitted_at)) {
                $report->submitted_at = now();
            }
        });
    }

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ReportAttachment::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ReportComment::class)->orderBy('created_at', 'desc');
    }

    public function assignedToUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeAnonymous($query)
    {
        return $query->where('is_anonymous', true);
    }

    public function scopeUrgent($query)
    {
        return $query->whereIn('urgency_level', ['high', 'critical']);
    }

    // Helper methods
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'submitted' => '#3B82F6',
            'under_review' => '#F59E0B',
            'investigating' => '#EF4444',
            'requires_more_info' => '#8B5CF6',
            'resolved' => '#10B981',
            'dismissed' => '#6B7280',
            default => '#6B7280'
        };
    }

    public function getUrgencyColorAttribute()
    {
        return match($this->urgency_level) {
            'low' => '#10B981',
            'medium' => '#F59E0B',
            'high' => '#EF4444',
            'critical' => '#DC2626',
            default => '#6B7280'
        };
    }

    public function getDaysOpenAttribute()
    {
        return $this->submitted_at->diffInDays(now());
    }

    public function markAsReviewed()
    {
        $this->update([
            'status' => 'under_review',
            'reviewed_at' => now()
        ]);
    }

    public function markAsResolved($resolutionDetails = null)
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolution_details' => $resolutionDetails
        ]);
    }
}
