<?php

namespace App;
use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

//use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    //use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    //protected $dates = ['deleted_at'];

    protected $fillable = ['post_id', 'description', 'user_id', 'parent_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function comments_liked()
    {
        return $this->belongsToMany('App\User', 'comment_likes', 'comment_id', 'user_id');
    }

    public function replies()
    {
        return $this->hasMany('App\Comment', 'parent_id', 'id');
    }

    public function reports()
    {
        return $this->belongsToMany('App\User', 'comment_reports', 'comment_id', 'reporter_id')->withPivot('status');
    }
    public function manageCommentReport($comment_id, $user_id, $description)
    {
        $comment_report = DB::table('comment_reports')->insert(['comment_id' => $comment_id, 'reporter_id' => $user_id,'description'=>$description ,'status' => 'pending', 'created_at' => Carbon::now()]);

        $result = $comment_report ? true : false;

        return $result;
    }
    public function users_tagged()
    {
        return $this->belongsToMany('App\User', 'comment_tags', 'comment_id', 'user_id');
    }
}

