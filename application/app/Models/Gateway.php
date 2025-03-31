<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gateway extends Model
{
    /** @use HasFactory<\Database\Factories\GatewayFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'is_active',
        'priority',
        'url',
        'data',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'priority' => 'integer',
            'data' => 'array',
        ];
    }
}
