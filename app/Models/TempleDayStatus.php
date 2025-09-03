<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempleDayStatus extends Model
{
    use HasFactory;
    protected $fillable = ['temple_id', 'date', 'is_closed', 'reason'];
}
