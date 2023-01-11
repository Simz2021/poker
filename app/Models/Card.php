<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['name'];

    public function getNameAttribute()
    {
        return $this->no . ' of ' . $this->suite;
    }
}
