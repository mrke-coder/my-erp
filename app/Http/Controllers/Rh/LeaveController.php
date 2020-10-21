<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;
use App\Models\RH\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller
{
    private $_rules =[
        'leave_date' => 'required|date',
        'leave_end_date' => 'required|date',
        'patterns' => 'required',
        'employee_id' => 'required'
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $leaves = Leave::withTrashed()
           ->join('employees','leaves.employee_id','=','employees.id')
           ->select('employees.*',
               'leaves.id as leave_id','leaves.leave_date as start','leaves.leave_end_date as end',
               'leaves.patterns as patterns', 'leaves.deleted_at as leave_deleted_at')
           ->orderBy('created_at','DESC')->paginate(10);

       return response()->json($leaves,200);
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

       $leave = new Leave();

       $leave->leave_date = $request->leave_date;
       $leave->leave_end_date = $request->leave_end_date;
       $leave->patterns = $request->patterns;
       $leave->employee_id = $request->employee_id;

       if ($leave->save()){
           $data = Leave::query()
               ->join('employees','leaves.employee_id','=','employees.id')
               ->select('employees.*',
                   'leaves.id as leave_id','leaves.leave_date as start','leaves.leave_end_date as end',
                   'leaves.patterns as patterns', 'leaves.deleted_at as leave_deleted_at')
               ->where('leaves.id','=',$leave->id)
               ->first();
           return response()->json($data,201);
       } else {
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
        $leave = Leave::find($id);

        if (is_null($leave)){
            return response()->json('No data Match with id: '.$id,404);
        }

        return response()->json($leave,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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

        $leave = Leave::find($id);

        $leave->leave_date = $request->leave_date;
        $leave->leave_end_date = $request->leave_end_date;
        $leave->patterns = $request->patterns;
        $leave->employee_id = $request->employee_id;
        if ($leave->save()){
            $data = Leave::query()
                ->join('employees','leaves.employee_id','=','employees.id')
                ->select('employees.*',
                    'leaves.id as leave_id','leaves.leave_date as start','leaves.leave_end_date as end',
                    'leaves.patterns as patterns', 'leaves.deleted_at as leave_deleted_at')
                ->where('leaves.id','=',$leave->id)
                ->first();
            return response()->json($data,201);
        } else {
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
       $leave = Leave::find($id);

       if ($leave->delete()){
           return response()->json($leave);
       } else{
           return response()->json('Server error',500);
       }
    }
}
