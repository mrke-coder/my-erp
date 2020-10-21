<?php

namespace App\Models\RH;

use Illuminate\Database\Eloquent\Model;

class Departure extends Model
{
  protected $table="departures";
  protected $fillable=[
      'departure_date',
      'patterns',
      'employee_id'
  ];

    public function employee()
    {
        return $this->belongsTo('App\Models\RH\Employee');
    }
}
