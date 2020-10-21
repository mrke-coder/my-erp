<?php

namespace App\Models\RH;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Absence extends Model
{
   use SoftDeletes;
   protected $table="absences";
   protected $fillable=[
       'absence_date',
       'fin_absence',
       'patterns',
       'justified',
       'employee_id'
   ];

    public function employee()
    {
        return $this->belongsTo('App\Models\RH\Employee');
    }
}
