<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;
use App\Models\RH\AdditionalHour;
use App\Models\RH\Bonus;
use App\Models\RH\Department;
use App\Models\RH\Employee;
use App\Models\RH\Leave;
use App\Models\RH\Speciality;
use App\Models\RH\Wage;
use App\User;
use Illuminate\Http\Request;

class RestoreDeleteController extends Controller
{

    public function departement(int $id)
    {
        $department = Department::onlyTrashed()->where('id','=',$id)->first();
        if ($department->restore()){
            return response()->json($department);
        } else{
            return response()->json("Server Error",500);
        }

   }

    public function speciality(int $id)
    {
        $speciality = Speciality::onlyTrashed()->where('id','=',$id)->first();
        if ($speciality->restore()){
            return response()->json($speciality);
        } else{
            return response()->json('Server Error', 500);
        }
   }

    public function employee(int $id)
    {
        $employee = Employee::onlyTrashed()->where('id','=',$id)->first();

        if ($employee->restore()){
            return response()->json('L\'employée '.$employee->lastName.' '.$employee->firstName.' précédemment supprimé a été restoré avec succès');
        } else{
            return response()->json('Server Error',500);
        }
   }

    public function wage(int $id)
    {
        $wage = Wage::onlyTrashed()->where('id','=',$id)->first();
        if ($wage->restore()){
           return response()->json('Le salaire précédemment supprimé a été restoré avec succès');
        } else{
            return response()->json('Server Error',500);
        }
   }

    public function bonus(int $id)
    {
        $bonus = Bonus::onlyTrashed()->where('id',$id)->first();

        if ($bonus->restore()){
            return response()->json('La prime précédemment supprimés a été restorée avec succès');
        } else{
            return response()->json('Server Error',500);
        }
   }

    public function leave(int $id)
    {
        $leave = Leave::onlyTrashed()->where('id',$id)->first();
        if ($leave->restore()){
            return response()->json('Récupération effectuée avec succès');
        } else{
            return response()->json('Server Error',500);
        }
   }

    public function add_hour(int $id)
    {
        $add_h = AdditionalHour::onlyTrashed()->where('id',$id)->first();

        if ($add_h->restore()){
            return response()->json("Réccupération effectuée avec succès");
        } else{

            return response()->json("Server error", 500);
        }
   }

    public function allDepartement()
    {
        return response()->json(Department::orderBy('name','DESC')->get());
     }

    public function allSpecialities()
    {
        return response()->json(Speciality::query()->select('name as text','id as value')->orderBy('name','DESC')->get());
     }

    public function allEmployees()
    {
        $employees = Employee::all();

        foreach ($employees as $employee){
           $data[] = [
               'text' => $employee->firstName.' '.$employee->lastName,
               'value' => $employee->id
           ];
        }

        return response()->json($data);
    }
}
