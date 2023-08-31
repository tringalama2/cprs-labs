<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Micro extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'label',
        'order_column',
    ];
}
