<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Book extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'author',
        'isbn',
        'description',
        'category',
        'publisher',
        'published_year',
        'total_copies',
        'available_copies',
        'cover_image',
        'added_by'
    ];

    protected $casts = [
        'published_year' => 'integer',
        'total_copies' => 'integer',
        'available_copies' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($book) {
            $book->slug = static::generateUniqueSlug($book->title);
        });

        static::updating(function ($book) {
            if ($book->isDirty('title')) {
                $book->slug = static::generateUniqueSlug($book->title, $book->id);
            }
        });
    }

    public static function generateUniqueSlug($title, $id = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    public function activeBorrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class)->where('status', 'borrowed');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'book_category');
    }

    public function collections(): HasMany
    {
        return $this->hasMany(Collection::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }

    public function isAvailable(): bool
    {
        // Hitung jumlah peminjaman aktif
        $activeBorrowingsCount = $this->activeBorrowings()->count();
        
        // Hitung salinan yang benar-benar tersedia
        $actualAvailableCopies = $this->total_copies - $activeBorrowingsCount;
        
        // Buku tersedia jika masih ada salinan yang belum dipinjam
        return $actualAvailableCopies > 0;
    }
    
    public function getActualAvailableCopies(): int
    {
        $activeBorrowingsCount = $this->activeBorrowings()->count();
        return max(0, $this->total_copies - $activeBorrowingsCount);
    }
    
    public function isBorrowedByUser($userId): bool
    {
        return $this->activeBorrowings()->where('user_id', $userId)->exists();
    }

    public function scopeAvailable($query)
    {
        return $query->where('available_copies', '>', 0);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
