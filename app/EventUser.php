<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventUser extends Model
{
    protected $table = 'event_user';
    protected $fillable = ['event_id','user_id','paid','transaction','status','created_at','updated_at'];
}
