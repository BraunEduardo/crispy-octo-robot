<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory, SoftDeletes;

    const DENIED = 'denied';
    const ERROR = 'error';
    const PROCESSING = 'processing';
    const PROCESSED = 'processed';
    const REFUNDED = 'refunded';

    const STATUSES = [
        self::DENIED,
        self::ERROR,
        self::PROCESSING,
        self::PROCESSED,
        self::REFUNDED,
    ];

    protected $fillable = [
        'client',
        'gateway',
        'status',
        'amount',
        'card_last_numbers',
    ];

    protected $hidden = [
        'clientRelation',
        'gatewayRelation',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            do {
                $uuid = Str::uuid();
            } while (static::where('external_id', $uuid)->exists());

            $model->external_id = $uuid;
        });
    }


    public function clientRelation() {
        return $this->belongsTo(Client::class, 'client');
    }

    public function gatewayRelation() {
        return $this->belongsTo(Gateway::class, 'gateway');
    }

    public function products() {
        return $this->belongsToMany(Product::class, 'transaction_products')->withPivot(['quantity']);
    }
}
