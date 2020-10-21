<?php

namespace App\Models\RH;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;
    protected $table='departments';
    protected $fillable=[
        'name'
    ];

    public function speciality()
    {
        return $this->hasMany('App\Models\RH\Speciality');
    }
}
