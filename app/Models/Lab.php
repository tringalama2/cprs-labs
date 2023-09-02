<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function panel(): BelongsTo
    {
        return $this->belongsTo(Panel::class);
    }
}
