<?php

namespace App\Models\RH;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
   protected $table='job_applications';
   protected $fillable =[
       'cover_letter',
       'cv',
       'contract_type',
       'requested_position',
       'request_type'
   ];
}
