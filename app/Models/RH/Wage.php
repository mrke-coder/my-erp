<?php

namespace App\Models\RH;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wage extends Model
{
    use SoftDeletes;
   protected $table="wages";
   protected $fillable = [
       'amount',
       'employee_id'
   ];

    public function employee()
    {
        return $this->belongsTo('App\Models\RH\Employee');
    }
}
