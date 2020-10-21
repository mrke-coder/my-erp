<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;
use App\Models\RH\Departure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartureController extends Controller
{
    private $_rules =['patterns' => 'required'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $departure = Departure::orderBy('created_at','DESC')->paginate(10);
       if (count($departure)===0){
           return response()->json('No data',404);
       }

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

        $departure->patterns = $request->patterns;
        $departure->employee_id = $request->employeeId;

        if ($departure->save()){
            return response()->json($departure,201);
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
        $departure = Departure::find($id);

        if (is_null($departure)){
            return response()->json('data not fund',404);
        }

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
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), $this->_rules);

        if ($validator->fails()){return response()->json($validator->errors(),422);}

        $departure = Departure::find($id);

        if (is_null($departure)){
            return response()->json('Data not fund', 404);
        }

        $departure->patterns = $request->patterns;

        if ($departure->save()){
            return response()->json($departure,201);
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
