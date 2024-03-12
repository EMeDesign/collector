<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Construction extends Model
{
    use HasFactory;
    use Relationships\Construction;
    use Scopes\Construction;

    protected $fillable = [
        'name',
        'image',
        'location',
        'description',
        'position',
    ];
}
