<?php

namespace App\Models\RH;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leave extends Model
{
    use SoftDeletes;
   protected $table='leaves';
   protected $fillable=[
       'leave_date',
       'leave_end_date',
       'patterns',
       'employee_id'
   ];

    public function employee()
    {
        return $this->belongsTo('App\Models\RH\Employee');
   }
}
