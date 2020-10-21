<?php

namespace App\Models\RH;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;
   protected $table="employees";
   protected $fillable=[
            'lastName',
            'firstName',
            'cni_reference',
            'adress',
            'ville',
            'contact',
            'email',
            'birth_day',
            'family_situation',
            'hire_date',
            'child_number',
            'diploma',
            'speciality_id',
            'user_id'
   ];

    public function cv()
    {
        return $this->hasOne('App\Models\RH\CV');
     }

    public function leave()
    {
        return $this->hasMany('App\Models\RH\Leave');
     }

    public function training()
    {
        return $this->hasMany('App\Models\RH\Training');
    }

    public function absence()
    {
        return $this->hasMany('App\Models\RH\Absence');
    }

    public function addtionalHour()
    {
        return $this->hasMany('App\Models\RH\AdditionalHour');
    }

    public function bonus()
    {
        return $this->hasMany('App\Models\RH\Bonus');
    }

    public function departure()
    {
        return $this->hasMany('App\Models\RH\Departure');
    }

    public function displacement()
    {
        return $this->hasMany('App\Models\RH\Displacement');
    }

    public function wage()
    {
        return $this->hasMany('App\Models\RH\Wage');
    }
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function speciality()
    {
        return $this->belongsTo('App\Models\RH\Speciality');
    }
}
