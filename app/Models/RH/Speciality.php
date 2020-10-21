<?php

namespace App\Models\RH;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Speciality extends Model
{
    use SoftDeletes;
    protected $table='specialities';
    protected $fillable=[
        'name',
        'description',
        'department_id'
    ];

    public function department()
    {
        return $this->belongsTo('App\Models\RH\Speciality');
    }

    public function employee()
    {
        return $this->hasMany('App\Models\RH\Employee');
    }
}
