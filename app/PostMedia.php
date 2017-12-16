<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostMedia extends Model
{
    //
	protected $table = 'post_media';
	protected $fillable = ['id','post_id','media_id','created_at','updated_at'];

}

