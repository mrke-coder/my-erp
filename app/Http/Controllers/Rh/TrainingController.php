<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;
use App\Models\RH\Training;
use App\Repositories\RhRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrainingController extends Controller
{
    private $_rules =[
        'type' => 'required',
        'start_date' => ['required','date'],
        'end_date' => ['required','date'],
        'employee_id' => ['required']
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trainings = Training::withTrashed()
            ->join('employees','employees.id','=','trainings.employee_id')
            ->select('employees.*',
                'trainings.start_date as start_date', 'trainings.end_date as end_date', 'trainings.type as type',
                'trainings.id as training_id', 'trainings.deleted_at as training_deleted_at', 'trainings.duration as duration')
            ->orderBy('created_at','DESC')->paginate(10);

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
           return response()->json($validator->errors(),422);
       }

       if ($request->start_date > $request->end_date || $request->start_date === $request->end_date){
           return response()->json('Désolé, la date de début doit être inférieure à la date de fin',400);
       }
       $training = new Training();

       $duration = RhRepository::getDuration($request->start_date,$request->end_date);
       //dd($duration);
       $training->type = $request->type;
       $training->start_date = $request->start_date;
       $training->end_date = $request->end_date;
       $training->duration = $duration;
       $training->employee_id = $request->employee_id;

       if ($training->save()){
           $data = Training::query()
               ->join('employees','employees.id','=','trainings.employee_id')
               ->select('employees.*',
                   'trainings.start_date as start_date', 'trainings.end_date as end_date', 'trainings.type as type',
                   'trainings.id as training_id', 'trainings.deleted_at as training_deleted_at', 'trainings.duration as duration')
               ->where('trainings.id','=',$training->id)
               ->first();
           return response()->json($data, 201);
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

        if ($request->start_date > $request->end_date || $request->start_date === $request->end_date){
            return response()->json('Désolé, la date de début doit être inférieure à la date de fin',400);
        }

        $duration = RhRepository::getDuration($request->start_date,$request->end_date);
        $training = Training::find($id);

        $training->type = $request->type;
        $training->start_date = $request->start_date;
        $training->end_date = $request->end_date;
        $training->employee_id = $request->employee_id;
        $training->duration = $duration;

        if ($training->save()){
            $data = Training::query()
                ->join('employees','employees.id','=','trainings.employee_id')
                ->select('employees.*',
                    'trainings.start_date as start_date', 'trainings.end_date as end_date', 'trainings.type as type',
                    'trainings.id as training_id', 'trainings.deleted_at as training_deleted_at', 'trainings.duration as duration')
                ->where('trainings.id','=',$training->id)
                ->first();
            return response()->json($data, 201);
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
    public function destroy(int $id)
    {
       $training = Training::find($id);

       if ($training->delete()){
           return response()->json("Suppression de la formation est effective");
       } else{
           return response()->json('Server error', 500);
       }
    }
}
