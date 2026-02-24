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
        'return_reason',
        'return_notes',
        'returned_by',
        'penalty_amount',
        'penalty_type',
        'penalty_paid',
        'penalty_notes'
    ];

    protected $casts = [
        'borrowed_date' => 'date',
        'due_date' => 'date',
        'returned_date' => 'date',
        'penalty_paid' => 'boolean',
        'penalty_amount' => 'decimal:2',
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


    public function calculatePenalty(): float
    {
        $penalty = 0;

        // If already returned, check return reason
        if ($this->status === 'returned' && $this->return_reason) {
            switch ($this->return_reason) {
                case 'book_damaged':
                    $penalty += 50000; // Rp 50,000 for damaged book
                    break;
                case 'book_lost':
                    $penalty += 100000; // Rp 100,000 for lost book (minimum)
                    break;
            }
        }

        // Calculate late penalty
        if ($this->returned_date) {
            $daysLate = Carbon::parse($this->returned_date)->diffInDays($this->due_date, false);
            if ($daysLate < 0) { // Negative means late
                $penalty += abs($daysLate) * 1000; // Rp 1,000 per day
            }
        } elseif ($this->isOverdue()) {
            // Still borrowed and overdue
            $daysLate = $this->getDaysOverdue();
            $penalty += $daysLate * 1000; // Rp 1,000 per day
        }

        return $penalty;
    }

    /**
     * Get penalty type based on return reason
     */
    public function getPenaltyType(): string
    {
        if ($this->return_reason === 'book_damaged') {
            return 'damaged';
        } elseif ($this->return_reason === 'book_lost') {
            return 'lost';
        } elseif ($this->isOverdue() || ($this->returned_date && Carbon::parse($this->returned_date)->gt($this->due_date))) {
            return 'late';
        }
        
        return 'none';
    }

    /**
     * Apply penalty to this borrowing
     */
    public function applyPenalty(): void
    {
        $penaltyAmount = $this->calculatePenalty();
        $penaltyType = $this->getPenaltyType();

        if ($penaltyAmount > 0) {
            $this->update([
                'penalty_amount' => $penaltyAmount,
                'penalty_type' => $penaltyType,
                'penalty_paid' => false,
            ]);
        }
    }

    /**
     * Mark penalty as paid
     */
    public function markPenaltyAsPaid(?string $notes = null): void
    {
        $this->update([
            'penalty_paid' => true,
            'penalty_notes' => $notes,
        ]);
    }
}
