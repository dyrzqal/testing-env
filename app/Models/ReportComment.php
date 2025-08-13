<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportComment extends Model
{
    protected $fillable = [
        'report_id',
        'user_id',
        'comment',
        'comment_type',
        'visibility',
        'status_change',
        'is_system_generated'
    ];

    protected $casts = [
        'status_change' => 'array',
        'is_system_generated' => 'boolean'
    ];

    // Relationships
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeVisibleToReporter($query)
    {
        return $query->where('visibility', 'reporter_visible');
    }

    public function scopeInternal($query)
    {
        return $query->where('visibility', 'internal');
    }

    public function scopeStatusChanges($query)
    {
        return $query->where('comment_type', 'status_change');
    }

    // Helper methods
    public function getCommentTypeColorAttribute()
    {
        return match($this->comment_type) {
            'internal' => '#6B7280',
            'status_change' => '#3B82F6',
            'request_info' => '#F59E0B',
            'resolution' => '#10B981',
            default => '#6B7280'
        };
    }

    public function isStatusChange(): bool
    {
        return $this->comment_type === 'status_change';
    }

    public function isVisibleToReporter(): bool
    {
        return $this->visibility === 'reporter_visible';
    }

    public static function createSystemComment(int $reportId, string $comment, array $statusChange = null)
    {
        return self::create([
            'report_id' => $reportId,
            'user_id' => auth()->id(),
            'comment' => $comment,
            'comment_type' => $statusChange ? 'status_change' : 'internal',
            'visibility' => 'internal',
            'status_change' => $statusChange,
            'is_system_generated' => true
        ]);
    }
}
