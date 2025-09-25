<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute; 
class Ebook extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'cover_image_path',
        'ebook_file_path',
        'discount_percentage',
        'language', 
        'type',     
        'price',    
    ];
    protected $appends = ['discounted_price'];
    protected $casts = [
        'price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    protected function discountedPrice(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                if ($attributes['type'] === 'free') {
                    return 0;
                }

                $originalPrice = $attributes['price'];
                $discount = $attributes['discount_percentage'] ?? 0;

                if ($discount > 0) {
                    $discountAmount = ($originalPrice * $discount) / 100;
                    return round($originalPrice - $discountAmount, 2);
                }

                return $originalPrice;
            }
        );
    }
}