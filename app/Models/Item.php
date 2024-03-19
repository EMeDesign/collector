<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    use Scopes\Item;
    use Relationships\Item;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_private'  => 'bool',
        'obtained_at' => 'datetime',
        'expired_at'  => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'description',
        'quantity',
        'image',
        'is_private',
        "unit_id",
        "furniture_id",
        "category_id",
        "obtained_at",
        "expired_at",
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'creator',
        'furniture',
        'unit',
    ];
}
