<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reputation extends Model
{
    use HasFactory;

    protected $fillable = ['reputation_points', 'is_approved'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}