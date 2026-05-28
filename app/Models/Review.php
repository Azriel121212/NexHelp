<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'reviewer_id',
        'helper_id',
        'rating',
        'comment',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function helper()
    {
        return $this->belongsTo(User::class, 'helper_id');
    }
}
