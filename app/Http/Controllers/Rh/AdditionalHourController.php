<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;
use App\Models\RH\AdditionalHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdditionalHourController extends Controller
{
   private $_rules =[
            'start' => 'required',
            'end' => 'required',
            'patterns' => 'required',
        ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $addHours = AdditionalHour::withTrashed()
            ->join('employees','additional_hours.employee_id','=','employees.id')
            ->select('employees.*',
                'additional_hours.start as start', 'additional_hours.end as end', 'additional_hours.patterns as patterns',
                'additional_hours.id as add_hour_id', 'additional_hours.deleted_at as add_hour_deleted_at')
            ->orderBy('created_at','DESC')->paginate(10);

        if (count($addHours)===0){
            return response()->json('No data',404);
        }

        return response()->json($addHours, 200);
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

       if($validator->fails()){
           return response()->json($validator->errors(),422);
       }

       if ($request->start === $request->end){
           return response()->json("La date ou l'heure du début doit être strictement différent de la date ou l'heure de fin",400);
       }

       $addHour = new AdditionalHour();

       $addHour->start = $request->start;
       $addHour->end = $request->end;
       $addHour->patterns = $request->patterns;
       $addHour->employee_id = $request->employee_id;

       if ($addHour->save()){
           $data = AdditionalHour::query()
               ->join('employees','additional_hours.employee_id','=','employees.id')
               ->select('employees.*',
                   'additional_hours.start as start', 'additional_hours.end as end', 'additional_hours.patterns as patterns',
                   'additional_hours.id as add_hour_id', 'additional_hours.deleted_at as add_hour_deleted_at')
               ->where('additional_hours.id', '=',$addHour->id)
                ->first();
           return response()->json($data, 201);
       } else{
           return response()->json('Server error', 500);
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
        $addHour = AdditionalHour::find($id);

        if (is_null($addHour)){
            return response()->json('No data',404);
        }

        return response()->json($addHour,200);
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

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        } else{

            $addHour = AdditionalHour::find($id);
            $addHour->start = $request->start;
            $addHour->end = $request->end;
            $addHour->patterns = $request->patterns;
            $addHour->employee_id = $request->employee_id;

            if ($addHour->save()){
                $data = AdditionalHour::query()
                    ->join('employees','additional_hours.employee_id','=','employees.id')
                    ->select('employees.*',
                        'additional_hours.start as start', 'additional_hours.end as end', 'additional_hours.patterns as patterns',
                        'additional_hours.id as add_hour_id', 'additional_hours.deleted_at as add_hour_deleted_at')
                    ->where('additional_hours.id', '=',$addHour->id)
                    ->first();
                return response()->json($data, 201);
            } else{
                return response()->json('Server error', 500);
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
        $addH = AdditionalHour::find($id);

        if ($addH->delete()){
            return response()->json('Suppression effectuée avec succès');
        } else{
            return response()->json('Server error',500);
        }
    }
}
