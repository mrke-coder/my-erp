<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;
use App\Models\RH\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
   private $_rules = [
       'lastName' => 'required',
       'firstName' => 'required',
       'cni_reference' => 'required',
       'ville' => 'required',
       'adress' => 'required',
       'contact' => 'required',
       'birth_day' => 'required|date',
       'family_situation'=>'required',
       'hire_date'=> 'required|date',
       'child_number'=>'required',
       'diploma' => 'required',
       'speciality_id'=>'required'
   ];
    public function index()
    {
       $employees = Employee::orderBy('created_at','DESC')
           ->withTrashed()
           ->join('specialities', 'specialities.id','=','employees.speciality_id')
           ->select('employees.*','specialities.name as speciality')
           ->paginate(10);

       return response()->json($employees, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),$this->_rules);
        if ($validator->fails()){
            return response()->json($validator->errors(),422);
        } else{
            $employee = Employee::create([
                'civility' => $request->civility,
                'lastName' => $request->lastName,
                'firstName' => $request->firstName,
                'cni_reference' => $request->cni_reference,
                'adress' => $request->adress,
                'ville'=> $request->ville,
                'contact'=> $request->contact,
                'email' => $request->email,
                'birth_day' => $request->birth_day,
                'family_situation' => $request->family_situation,
                'hire_date' => $request->hire_date,
                'child_number' => $request->child_number,
                'diploma'=> $request->diploma,
                'speciality_id' => $request->speciality_id,
                'user_id' => Auth::id()
            ]);
            $data = Employee::orderBy('created_at','DESC')
                ->withTrashed()
                ->join('specialities', 'specialities.id','=','employees.speciality_id')
                ->select('employees.*','specialities.name as speciality')
                ->where('employees.id','=',$employee->id)
                ->first();
           return response()->json($data, 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::find($id);
        if (is_null($employee)){
            return response()->json(null, 404);
        }
        return response()->json($employee,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $employee = Employee::find($id);

      $validator = Validator::make($request->all(), $this->_rules);

      if ($validator->fails()){
          return response()->json($validator->errors(),422);
      } else {
          $employee->civility = $request->civility;
          $employee->lastName = $request->lastName;
          $employee->firstName = $request->firstName;
          $employee->cni_reference = $request->cni_reference;
          $employee->adress = $request->adress;
          $employee->ville = $request->ville;
          $employee->contact = $request->contact;
          $employee->email = $request->email;
          $employee->family_situation = $request->family_situation;
          $employee->birth_day = $request->birth_day;
          $employee->hire_date = $request->hire_date;
          $employee->child_number = $request->child_number;
          $employee->diploma = $request->diploma;
          $employee->speciality_id = $request->speciality_id;
          if ($employee->save()) {
              return response()->json($employee, 201);
          }
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $employee = Employee::find($id);
        if ($employee->delete()){
          return  response()->json([
              'data' => Employee::onlyTrashed()->where('id','=',$id)->first(),
              'message' => 'Employé N°'.$id.' a été supprimer avec succès'
          ]);
        }
    }
}
