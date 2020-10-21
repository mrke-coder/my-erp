<?php

namespace App\Models\RH;

use Illuminate\Database\Eloquent\Model;

class CV extends Model
{
   protected $table ='cvs';
   protected $fillable = [
       'id',
       'name',
       'speciality',
       'employee_id'
   ];

    public function employee()
    {
       return $this->belongsTo('App\Models\RH\Employee');
    }
}
