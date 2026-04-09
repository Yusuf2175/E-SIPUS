<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Borrowing extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'borrowed_date',
        'due_date',
        'returned_date',
        'status',
        'notes',
        'approved_by',
        'approved_at',
        'reject_reason',
        'return_reason',
        'return_notes',
        'returned_by',
        'return_requested_at',
        'hidden_by_user',
        'hidden_at'
    ];

    protected $casts = [
        'borrowed_date'       => 'date',
        'due_date'            => 'date',
        'returned_date'       => 'date',
        'approved_at'         => 'datetime',
        'return_requested_at' => 'datetime',
        'hidden_at'           => 'datetime',
        'hidden_by_user'      => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function returnedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'returned_by');
    }

    public function isOverdue(): bool
    {
        return $this->status === 'borrowed' && $this->due_date < Carbon::now()->toDateString();
    }

    public function getDaysOverdue(): int
    {
        if (!$this->isOverdue()) {
            return 0;
        }
        
        return Carbon::now()->diffInDays($this->due_date);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'borrowed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'borrowed')
                    ->where('due_date', '<', Carbon::now()->toDateString());
    }

    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopePendingReturn($query)
    {
        return $query->where('status', 'pending_return');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isPendingReturn(): bool
    {
        return $this->status === 'pending_return';
    }

    // Scope untuk filter borrowing yang tidak di-hide oleh user
    public function scopeVisibleToUser($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->where('user_id', '!=', $userId)
              ->orWhere('hidden_by_user', false);
        });
    }

    // Method untuk hide history
    public function hideFromUser(): bool
    {
        if ($this->status === 'returned') {
            $this->update([
                'hidden_by_user' => true,
                'hidden_at' => Carbon::now()
            ]);
            return true;
        }
        return false;
    }

    // Method untuk unhide history (jika diperlukan)
    public function unhideFromUser(): bool
    {
        $this->update([
            'hidden_by_user' => false,
            'hidden_at' => null
        ]);
        return true;
    }
}
