<?php

namespace App\Models;

use App\Models\Book;
use App\Models\Preview;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'country_id', 'city_id', 'description'];

    /**
     * Get all books related to this exchange.
     */
    public function books()
    {
        return $this->hasMany(Book::class);
    }

    /**
     * Get All preview images related to this exchange.
     */
    public function previews()
    {
        return $this->hasMany(Preview::class);
    }
}
