<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Exchange;

class Preview extends Model
{
    use HasFactory;

    protected $fillable = ['exchange_id', 'image'];

    public function exchange()
    {
        return $this->belongsTo(Exchange::class);
    }
}
