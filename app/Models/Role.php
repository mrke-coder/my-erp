<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
   protected $table="roles";
   protected $fillable=['role'];

    public function users()
    {
        return $this->belongsToMany('App\User')
            ->using('App\Models\UserRole')
            ->withPivot('user_id','role_id');
     }
}
