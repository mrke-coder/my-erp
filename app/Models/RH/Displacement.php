<?php

namespace App\Models\RH;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Displacement extends Model
{
    use SoftDeletes;
    protected $table="displacements";
    protected $fillable=[
        'displacement_date',
        'return_date',
        'destination',
        'means',
        'costs',
        'accommodation_costs',
        'employee_id'
    ];

    public function employee()
    {
        return $this->belongsTo('App\Models\RH\Employee');
    }
}
