<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   
    protected $fillable = [
        'tournament_id', 'user_id','score'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

}
