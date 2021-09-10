<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Exchange;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status', 'exchange_id'];

    protected $hidden = ['updated_at'];

    public function exchanges()
    {
        $this->belongsTo(Exchange::class);
    }
}
