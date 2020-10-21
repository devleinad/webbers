<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bounty extends Model
{
    protected $fillable = ['bounty_points', 'status'];
    use HasFactory;

    public function post()
    {
        return $this->belongsTo('App\Models\Post');
    }
}