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

    public function reports()
    {
        return $this->hasMany(Report::class, 'task_id');
    }

    public static function cleanupExpiredPendingTasks()
    {
        $allPendingTasks = self::with('requester')->where('status', 'Pending Approval')->get();
        
        foreach ($allPendingTasks as $pt) {
            $taskDateTime = \Carbon\Carbon::parse($pt->schedule_date . ' ' . $pt->start_time);
            if ($taskDateTime->isPast()) {
                // Expired! Cancel it and return points
                $pt->status = 'cancelled';
                $pt->reject_reason = 'Dibatalkan Otomatis: Waktu tugas sudah terlewat sebelum disetujui Admin.';
                $pt->save();
                
                // Return points to requester
                if ($pt->requester) {
                    $pt->requester->increment('points', $pt->reward_points);
                }
            }
        }
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
