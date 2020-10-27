<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;
use App\Models\RH\Departure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartureController extends Controller
{
    private $_rules =[
        'patterns' => 'required|max:200',
        'departure_date' => 'required',
        'employee_id' => 'required'
        ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $departure = Departure::withTrashed()
           ->join('employees', 'employees.id', '=', 'departures.employee_id')
           ->select('departures.*',
               'employees.firstName as prenom','employees.lastName as nom')
           ->orderBy('created_at','DESC')->paginate(10);

       return response()->json($departure,200);
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

        if ($validator->fails()){return response()->json($validator->errors(),422);}

        $departure = new Departure();

        $departure->departure_date = $request->departure_date;
        $departure->patterns = $request->patterns;
        $departure->employee_id = $request->employee_id;

        if ($departure->save()){
            $data = Departure::query()
                ->join('employees', 'employees.id', '=', 'departures.employee_id')
                ->select('departures.*',
                    'employees.firstName as prenom','employees.lastName as nom')
                ->where('departures.id','=', $departure->id)
                ->first();
            return response()->json($data,201);
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
    public function show(int $id)
    {
        $departure = Departure::find($id);

        return response()->json($departure,200);
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
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), $this->_rules);

        if ($validator->fails()){return response()->json($validator->errors(),422);}

        $departure = Departure::find($id);

        $departure->patterns = $request->patterns;
        $departure->employee_id = $request->employee_id;
        $departure->departure_date = $request->departure_date;

        if ($departure->save()){
            $data = Departure::query()
                ->join('employees', 'employees.id', '=', 'departures.employee_id')
                ->select('departures.*',
                    'employees.firstName as prenom','employees.lastName as nom')
                ->where('departures.id','=', $departure->id)
                ->first();
            return response()->json($data,201);
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
        $departure = Departure::find($id);

        if ($departure->delete()) {
            return response()->json('Suppression de la sauvegarde a été effectuée avec succès');
        } else {
            return  response()->json("Server error", 500);
        }
    }
}
