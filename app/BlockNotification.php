<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlockNotification extends Model
{
    protected $table = 'block_notification';
    public $timestamps = false;
    protected $fillable = ['id','user_id','post_event_id','type'];
}
