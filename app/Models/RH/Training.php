<?php

namespace App\Models\RH;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
   protected $table="trainings";
   protected $fillable=[
       'type',
       'start_date',
       'end_date',
       'duration',
       'employee_id'
   ];

    public function employee()
    {
        return $this->belongsTo('App\Models\RH\Employee');
   }
}
