<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;


    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];


    protected $fillable  = ['user_id', 'name', 'image', 'description', 'start_time', 'end_time', 'location', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticket()
    {
        return $this->hasMany(Ticket::class);
    }
}
