<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;
use App\Models\RH\Speciality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SpecialityController extends Controller
{
    private $_rules = [
        'name' => 'required',
        'department_id' => 'required'
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $specialties = Speciality::orderBy('created_at','DESC')
           ->withTrashed()
           ->paginate(10);
       return  response()->json($specialties,200);

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
        $validator = Validator::make($request->all(), $this->_rules);

        if ($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        $speciality = Speciality::updateOrCreate(['id'=>$request->speciality_id],
            [
                'name' => $request->name,
                'description' => $request->description,
                'department_id' => $request->department_id
            ]
            );

        if ($speciality) {
            return response()->json(Speciality::query()
                ->join('departments','specialities.department_id','=','departments.id')
                ->select('specialities.*','departments.name as department_name')
                ->where(['specialities.id'=>$speciality->id])
                ->first(),201);
        } else{
            return response()->json('Server error',500);
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
       $speciality = Speciality::find($id);
       if (is_null($speciality)){
           return response()->json('No data match with id: '.$id);
       }

       return response()->json($speciality,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $validator = Validator::make($request->all(), $this->_rules);

        if ($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $speciality = Speciality::find($id);

        $speciality->name = $request->name;
        $speciality->description = $request->description;

        if ($speciality->save()){
            return response()->json($speciality,201);
        } else{
            return response()->json('Server error',500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       Speciality::find($id)->delete();
       return response()->json('Suppression effectuée avec succès');
    }
}
