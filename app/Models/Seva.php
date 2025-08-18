<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seva extends Model
{
    use HasFactory;
    protected $fillable = ['temple_id', 'name', 'description', 'price', 'type'];

    /**
     * Get the temple that this seva belongs to.
     */
    public function temple()
    {
        return $this->belongsTo(Temple::class);
    }
}
