<?php

namespace App\Models\RH;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departure extends Model
{
  use SoftDeletes;
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
