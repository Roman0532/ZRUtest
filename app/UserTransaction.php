<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTransaction extends Model
{
    protected $table = 'users_transactions';

    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'amount',
        'status_id',
        'dispatch_time'
    ];

    public function status()
    {
        return $this->belongsTo(Status::class, 'id', 'status_id');
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id', 'id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id', 'id');
    }
}
