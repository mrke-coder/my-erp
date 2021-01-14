<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;
use App\Models\RH\Employee;
use Carbon\Carbon;

class RhController extends Controller
{
    public function employeesByDepartment(int $id)
    {
       $result = Employee::query()
       ->join('specialities','specialities.id', '=', 'employees.speciality_id')
       ->join('departments','specialities.department_id', '=', 'departments.id')
       ->select('employees.*')
       ->where('departments.id', '=', $id)
       ->get();


       return response()->json([
           'data' => $result,
           'nombre' => count($result)
       ]);
    }

    public function nearBirthday()
    {
       $employees = Employee::all();
       $employees_array = [];


       foreach ($employees as $employee) {
           $date = $employee->birth_day;
           $r = explode('-',$date);

           $f = $r[0].'-'.$r[1].'-'.$r[2];

           try {
               $bd = with(new Carbon($f))->format('Y-m-d');
               $nDRestant =Carbon::parse($bd)->floatDiffInDays(date('Y-m-d'));
               $employees_array[] = $employee;
               $employees_array[]['bd'] = $bd;
               $employees_array[]['nDRestant'] = $nDRestant;
           } catch (\Exception $e) {
           }

       }

       $i = 0;
       while ($i<count($employees_array)){
           if ($employees_array[$i]['nDRestant'] > $employees_array[$i+1]['nDRestant']){
               $tmp = $employees_array[$i]['nDRestant'];
               $employees_array[$i]['nDRestant'] = $employees_array[$i+1]['nDRestant'];
               $employees_array[$i+1]['nDRestant'] =$tmp;

               $i=0;
           }
           $i +=1;
       }

        return response()->json($employees_array);
    }
}
