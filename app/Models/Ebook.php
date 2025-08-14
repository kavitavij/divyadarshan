<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ebook extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'cover_image_path',
        'ebook_file_path',
        'language', 
        'type',     
        'price',    
    ];
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}