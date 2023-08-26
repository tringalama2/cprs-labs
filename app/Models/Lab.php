<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'label',
        'panel_id',
        'order_column',
    ];
}
