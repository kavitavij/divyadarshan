<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SevaBooking extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'seva_id', 'amount', 'status'];

    public function user() { return $this->belongsTo(User::class); }
    public function seva() { return $this->belongsTo(Seva::class); }
}