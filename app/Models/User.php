<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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
        'address',
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

    // Role methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPetugas()
    {
        return $this->role === 'petugas';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    // Relationship with role requests
    public function roleRequests()
    {
        return $this->hasMany(RoleRequest::class);
    }

    public function approvedRoleRequests()
    {
        return $this->hasMany(RoleRequest::class, 'approved_by');
    }

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
        return $this->hasMany(Borrowing::class)->where('status', 'borrowed');
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
