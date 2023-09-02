<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Panel extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'label',
        'order_column',
    ];

    public function labs(): HasMany
    {
        return $this->hasMany(Lab::class);
    }
}
