<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;
use App\Models\RH\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    private $_rules = ['name' => 'required'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dpts = Department::orderBy('created_at','DESC')->withTrashed()->paginate(10);
        return response()->json($dpts);
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
       }
       $dpt = new Department();
       $dpt->name = $request->name;
       if ($dpt->save()){
           return response()->json($dpt,200);
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
        $dpt = Department::find($id);
        if (is_null($dpt)){
            return response()->json('No data match with id: '.$id);
        }

        return response()->json($dpt,200);
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
        $validator = Validator::make($request->all(),$this->_rules);

        if ($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $dpt = Department::find($id);
        $dpt->name = $request->name;
        if ($dpt->save()){
            return response()->json($dpt,201);
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
        $depart =Department::find($id);
        $depart->delete();
        return response()->json($depart);
    }
}
