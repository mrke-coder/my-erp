<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;
use App\Models\RH\Displacement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DisplacementController extends Controller
{
    private $_rules = [
        'displacement_date' => 'required|date',
        'return_date' => 'required|date',
        'destination' => 'required',
        'means' => 'required|max:200',
        'costs' => 'required',
        'accommodation_costs' => 'required',
        'employee_id' => 'required'
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $displts = Displacement::withTrashed()
           ->join('employees', 'displacements.employee_id','=','employees.id')
           ->select('displacements.*',
               'employees.firstName as prenom', 'employees.lastName as nom')
           ->orderBy('created_at','DESC')
           ->paginate(10);
       return response()->json($displts);
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
           return response()->json($validator->errors(), 422);
       }

       $displt = new Displacement();

       $displt->displacement_date = $request->displacement_date;
       $displt->return_date = $request->return_date;
       $displt->destination = $request->destination;
       $displt->means = $request->means;
       $displt->costs = $request->costs;
       $displt->accommodation_costs = $request->accommodation_costs;
       $displt->employee_id = $request->employee_id;

       if ($displt->save()){
           $data = Displacement::query()
               ->join('employees', 'displacements.employee_id','=','employees.id')
               ->select('displacements.*',
                   'employees.firstName as prenom', 'employees.lastName as nom')
               ->where('displacements.id','=',$displt->id)
               ->first();
           return response()->json($data,201);
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
       $displcmt = Displacement::find($id);
       return response()->json($displcmt,200);
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
            return response()->json($validator->errors(), 400);
        }

        $displt = Displacement::find($id);

        $displt->displacement_date = $request->displacement_date;
        $displt->return_date = $request->return_date;
        $displt->destination = $request->destination;
        $displt->means = $request->means;
        $displt->costs = $request->costs;
        $displt->accommodation_costs = $request->accommodation_costs;

        if ($displt->save()){
            $data = Displacement::query()
                ->join('employees', 'displacements.employee_id','=','employees.id')
                ->select('displacements.*',
                    'employees.firstName as prenom', 'employees.lastName as nom')
                ->where('displacements.id','=',$displt->id)
                ->first();
            return response()->json($data,201);
        } else{
            return response()->json('Server error', 500);
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
       $mission = Displacement::find($id);

       if ($mission->delete()) {
           return response()->json("Suppression de de la mission a été effectuée avec succès");
       } else{
           return  response()->json("Server error", 500);
       }
    }
}
