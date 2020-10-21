<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['enable-notifications', 'ushured', 'ok_with_categories'];
    use HasFactory;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}