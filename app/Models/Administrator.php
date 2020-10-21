<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administrator extends Model
{
    protected $table="administrators";
    protected $fillable=['firstName','lastName','user_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
