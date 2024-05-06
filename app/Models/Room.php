<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    use Methods\Room;
    use Relationships\Room;

    protected $fillable = [
        'name',
        'image',
        'description',
        'position',
        'construction_id',
    ];
}
