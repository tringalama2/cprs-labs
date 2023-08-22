<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panel extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'label',
        'sort_id',
    ];
}
