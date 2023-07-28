<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;

class Task extends Model
{
    protected $dates = ['due_date'];

    use HasFactory;

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function routeNotificationForMail(Notification $notification)
    {
        return $this->assignedTo->email;
    }
}
