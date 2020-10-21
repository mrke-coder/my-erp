<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;
use App\Models\RH\Absence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AbsenceController extends Controller
{
    private $_rules = [
        'employee_id' => 'required',
        'absence_date' => 'required|date',
        'patterns' => 'required',
        'justified' => 'required|boolean',
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $absences = Absence::withTrashed()
           ->join('employees','employees.id','=','absences.employee_id')
           ->select('employees.*',
               'absences.absence_date as absence_date','absences.fin_absence as fin_absence','absences.patterns as patterns',
               'absences.justified as justified', 'absences.deleted_at as absence_deleted_at','absences.id as absence_id')
           ->orderBy('created_at','DESC')
           ->paginate(10);
       return response()->json($absences, 200);
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
      } else{
          $absence = new Absence();

          $absence->absence_date = $request->absence_date;
          $absence->fin_absence = $request->fin_absence;
          $absence->patterns = $request->patterns;
          $absence->justified = $request->justified;
          $absence->employee_id = $request->employee_id;

          if ($absence->save()){
              $data = Absence::query()
                  ->join('employees','employees.id','=','absences.employee_id')
                  ->select('employees.*',
                      'absences.absence_date as absence_date','absences.fin_absence as fin_absence','absences.patterns as patterns',
                      'absences.justified as justified', 'absences.deleted_at as absence_deleted_at','absences.id as absence_id')
                  ->where('absences.id','=',$absence->id)
                  ->first();
              return response()->json($data, 201);
          } else{
              return response()->json('Server error',500);
          }
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

       return response()->json(Absence::find($id));
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
        } else{
            $absence = Absence::find($id);
            if (is_null($absence)){
                return response()->json('Null',404);
            }
            $absence->absence_date = $request->absence_date;
            $absence->fin_absence = $request->fin_absence;
            $absence->patterns = $request->patterns;
            $absence->justified = $request->justified;

            if ($absence->save()){
                $data = Absence::query()
                    ->join('employees','employees.id','=','absences.employee_id')
                    ->select('employees.*',
                        'absences.absence_date as absence_date','absences.fin_absence as fin_absence','absences.patterns as patterns',
                        'absences.justified as justified', 'absences.deleted_at as absence_deleted_at','absences.id as absence_id')
                    ->where('absences.id','=',$absence->id)
                    ->first();
                return response()->json($data, 201);
            } else{
                return response()->json('Server error',500);
            }
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
        $absence = Absence::find($id);

        if ($absence->delete()){
            $data = Absence::onlyTrashed()->where('id','=',$id)->first();
            return response()->json($data);
        }
    }
}
