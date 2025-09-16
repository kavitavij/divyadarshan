<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seva extends Model
{
    use HasFactory;

    protected $fillable = [
        'temple_id',
        'name',
        'description',
        'price',
        'image',
        'is_active'
    ];

    /**
     * Define the relationship to the Temple model.
     * This completes the chain needed for the controller query.
     */
    public function temple()
    {
        return $this->belongsTo(Temple::class);
    }

}
