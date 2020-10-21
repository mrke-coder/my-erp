<?php

namespace App\Models\RH;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdditionalHour extends Model
{
    use SoftDeletes;
    protected $table="additional_hours";
    protected $fillable=[
        'number_of_hours',
        'patterns',
        'employee_id'
    ];

    public function employee()
    {
        return $this->belongsTo('App\Models\RH\Employee');
    }
}
