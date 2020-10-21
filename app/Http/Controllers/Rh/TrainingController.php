<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;
use App\Models\RH\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrainingController extends Controller
{
    private $_rules =[
        'type' => 'required',
        'duration' => 'required|numeric'
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trainings = Training::orderBy('created_at','DESC')->paginate(10);

        if (count($trainings)===0){
            return response()->json('No data',404);
        }

        return response()->json($trainings,200);
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
           return response()->json($validator->fails(),400);
       }

       $training = new Training();

       $training->type = $request->type;
       $training->duration = $request->duration;
       $training->employee_id = $request->employee;

       if ($training->save()){
           return response()->json($training, 201);
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
       $training = Training::find($id);

       if (is_null($training)){
           return response()->json('No data match with id: '.$id);
       }

       return response()->json($training,200);
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
            return response()->json($validator->fails(),400);
        }

        $training = Training::find($id);

        $training->type = $request->type;
        $training->duration = $request->duration;

        if ($training->save()){
            return response()->json($training, 201);
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
        //
    }
}
