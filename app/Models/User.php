<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'account_status',
        'rejection_reason',
        'address',
        'province',
        'city',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool    { return $this->role === 'admin'; }
    public function isPetugas(): bool  { return $this->role === 'petugas'; }
    public function isUser(): bool     { return $this->role === 'user'; }

    public function isPending(): bool  { return $this->account_status === 'pending'; }
    public function isApproved(): bool { return $this->account_status === 'approved'; }
    public function isRejected(): bool { return $this->account_status === 'rejected'; }

    /**
     * URL avatar — pakai foto upload jika ada, fallback ke inisial via UI Avatars.
     */
    public function avatarUrl(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name)
             . '&background=7c3aed&color=fff&size=128&bold=true&rounded=true';
    }

    // Relationship with role requests


    // Relationship with books
    public function addedBooks()
    {
        return $this->hasMany(Book::class, 'added_by');
    }

    // Relationship with borrowings
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function activeBorrowings()
    {
        return $this->hasMany(Borrowing::class)->whereIn('status', ['approved', 'borrowed', 'pending_return']);
    }

    public function approvedBorrowings()
    {
        return $this->hasMany(Borrowing::class, 'approved_by');
    }

    // Relationship with collections
    public function collections()
    {
        return $this->hasMany(Collection::class);
    }

    // Relationship with reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
