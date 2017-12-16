<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    //
	protected $fillable = ['id','follower_id','leader_id','status','created_at','updated_at'];
}
