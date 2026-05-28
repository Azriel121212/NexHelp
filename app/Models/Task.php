<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title', 
        'description', 
        'category', 
        'reward_points', 
        'location', 
        'deadline', 
        'status', 
        'requester_id', 
        'helper_id',
        'schedule_date',
        'start_time',
        'end_time'
    ];

    public function applications()
    {
        return $this->hasMany(TaskApplication::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function helper()
    {
        return $this->belongsTo(User::class, 'helper_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
