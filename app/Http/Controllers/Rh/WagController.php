<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;
use App\Models\RH\Wage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WagController extends Controller
{
    private $_rules =['amount' => 'required|numeric', 'employee_id'=> 'required'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wags = Wage::withTrashed()
            ->join('employees','employees.id','=','wages.employee_id')
            ->select('employees.*',
                'wages.amount as amount','wages.id as wage_id', 'wages.deleted_at as deleted_at')
            ->orderBy('created_at','DESC')
            ->paginate(10);

        return response()->json($wags);
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

       $wag = new Wage();

       $wag->amount = $request->amount;
       $wag->employee_id = $request->employee_id;

       if ($wag->save()){
           $data = Wage::query()
               ->join('employees','employees.id','=','wages.employee_id')
               ->select('employees.*',
                   'wages.amount as amount','wages.id as wage_id')
               ->where('wages.id','=',$wag->id)
               ->first();
           return response()->json($data, 200);
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
       $wag = Wage::find($id);
       if (is_null($wag)){
           return response()->json('No data matched with id: '.$id);
       }

       return response()->json($wag,200);
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
            return response()->json($validator->errors(),422);
        }

        $wag = Wage::find($id);

        $wag->amount = $request->amount;

        if ($wag->save()){
            $data = Wage::query()
                ->join('employees','employees.id','=','wages.employee_id')
                ->select('employees.*',
                    'wages.amount as amount','wages.id as wage_id')
                ->where('wages.id','=',$wag->id)
                ->first();
            return response()->json($data, 200);
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
        $wag = Wage::find($id);
        if ($wag->delete()){
             return response()->json("Salaire supprimé avec succès");
        } else{
            return response()->json('DELETED FAILED',500);
        }
    }
}
