<?php

namespace App\Models\RH;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bonus extends Model
{
    use SoftDeletes;
    protected $table="bonuses";
    protected $fillable =[
        'patterns',
        'amount',
        'employee_id'
    ];

    public function employee()
    {
        return $this->belongsTo('App\Models\RH\Employee');
    }
}
