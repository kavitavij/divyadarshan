<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpiritualHelpRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'contact_info', 'city', 'query_type', 'temple_id',
        'preferred_time', 'message', 'is_resolved'
    ];

    public function temple()
    {
        return $this->belongsTo(Temple::class);
    }
}
