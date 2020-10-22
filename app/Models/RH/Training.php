<?php

namespace App\Models\RH;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Training extends Model
{
    use SoftDeletes;
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
