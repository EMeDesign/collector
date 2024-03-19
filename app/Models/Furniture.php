<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Furniture extends Model
{
    use HasFactory;
    use Methods\Furniture;
    use Relationships\Furniture;
    use Scopes\Furniture;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_private' => 'bool',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'image',
        'description',
        'position',
        'is_private',
        'room_id',
    ];
}
