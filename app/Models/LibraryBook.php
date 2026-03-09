<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryBook extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function accesses()
    {
        return $this->hasMany(LibraryBookAccess::class, 'library_book_id');
    }

    public function getCoverAttribute($value)
    {
        if (!$value) return null;
        return config('app.url') . '/' . $value;
    }

    public function getIsFeaturedAttribute($value)
    {
        return (int) $value;
    }

    public function getStatusAttribute($value)
    {
        return (int) $value;
    }

    public function getPriceAttribute($value)
    {
        return (float) $value;
    }

    public function getPageCountAttribute($value)
    {
        return $value ? (int) $value : null;
    }
}
