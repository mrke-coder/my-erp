<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;
use App\Models\RH\Bonus;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class BonusController extends Controller
{
    private $_rules =[
        'patterns' => ['required','min:15'],
        'employee_id' => ['required'],
        'amount' => ['required','numeric']
    ];
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $bonuses = Bonus::withTrashed()
           ->join('employees','bonuses.employee_id','=','employees.id')
           ->select('employees.*',
               'bonuses.id as bonus_id','bonuses.amount as amount', 'bonuses.patterns as patterns', 'bonuses.deleted_at as bns_deleted_at')
           ->orderBy('bonuses.created_at','DESC')
           ->paginate(10);
       return response()->json($bonuses,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {

       $validator = Validator::make($request->all(), $this->_rules);

       if ($validator->fails()) {
           return response()->json($validator->errors(),422);
       }

       $bonus = Bonus::create([
            'patterns' => $request->patterns,
            'amount'=> $request->amount,
            'employee_id' => $request->employee_id
       ]);

       if ($bonus){
           $data = Bonus::query()
               ->join('employees','bonuses.employee_id','=','employees.id')
               ->select('employees.*',
                   'bonuses.id as bonus_id','bonuses.amount as amount', 'bonuses.patterns as patterns', 'bonuses.deleted_at as bns_deleted_at')
               ->where('bonuses.id','=',$bonus->id)->first();

           return response()->json($data, 201);
       } else{
           return response()->json('server error', 500);
       }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $bonus = Bonus::find($id);
        if (is_null($bonus)){
            return response()->json('No Data',404);
        }

        return response()->json($bonus,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
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
     * @return Response
     */
    public function update(Request $request, $id)
    {
       $bonus = Bonus::find($id);
       $validator = Validator::make($request->all(),$this->_rules);

       if ($validator->fails()){
           return response()->json($validator->errors(),422);
       }

       $bonus->patterns = $request->patterns;
       $bonus->amount = $request->amount;

       if ($bonus->save()){
           $data = Bonus::query()
               ->join('employees','bonuses.employee_id','=','employees.id')
               ->select('employees.*',
                   'bonuses.id as bonus_id','bonuses.amount as amount', 'bonuses.patterns as patterns', 'bonuses.deleted_at as bns_deleted_at')
               ->where('bonuses.id','=',$bonus->id)->first();
           return response()->json($data,201);
       } else{
           return response()->json('Server error',500);
       }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $bonus = Bonus::find($id);

        if ($bonus->delete()){
            return response()->json(Bonus::onlyTrashed()->where('id','=',$bonus->id)->first());
        }
    }
}
