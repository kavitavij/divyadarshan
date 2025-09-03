<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultDarshanSlot extends Model
{
    use HasFactory;
    protected $fillable = ['temple_id', 'start_time', 'end_time', 'capacity', 'is_active'];
}
