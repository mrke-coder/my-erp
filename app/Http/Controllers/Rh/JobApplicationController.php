<?php

namespace App\Http\Controllers\Rh;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobApplicationRequest;
use App\Models\RH\JobApplication;
use App\Repositories\RhRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class JobApplicationController extends Controller
{
  private $_rules = [
      'letter' => 'mimes:pdf',
      'cv'=> 'required|mimes:pdf',
      'contract'=>'required', // type de contrat
      'requested_position'=>'required', // poste souhaité
      'request_type'=>'required' // type de demande
  ];
    public function index()
    {
        $jobApp = JobApplication::orderBy('created_at','DESC')->paginate(10);
        if (!$jobApp){
            return response()->json(
                [
                    'message'=>'Aucune demande d\'emploi n\'a éte formulée',
                    'status_code' => 404
                ], 404
            );
        }

        return  response()->json($jobApp, 200);
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
           return response()->json($validator->errors(), 400);
       } else {
          $cv = RhRepository::uploadFile($request->file('cv'),'cv');
          $lm =null;
          if ($request->letter){$lm = RhRepository::uploadFile($request->file('letter'),'lm');}

          $jobApp = JobApplication::create(
              [
                  'cover_letter'=>$lm,
                  'cv'=>$cv,
                  'contract_type'=>$request->contract,
                  'requested_position'=> $request->requested_position,
                  'request_type' => $request->request_type
              ]
          );
          return response()->json($jobApp,200);
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
        $request_item = JobApplication::find($id);
        if (is_null($request_item)){
            return  response()->json(
                [
                   'message' => 'le numéro d\identification utilisé ne correspond à aucune demande d\'emploi',
                   'data'=> null,
                ], 404
            );
        }

        return response()->json($request_item,200);
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
       $request_item = JobApplication::find($id);
       $validator = Validator::make($request->all(), $this->_rules);
        if ($validator->fails()){
            return response()->json($validator->errors(),400);
        } else{
            $cv = RhRepository::uploadFile($request->file('cv'),'cv');
            $lm =null;
            if ($request->letter){$lm = RhRepository::uploadFile($request->file('lm'),'lm');}
            JobApplication::update(['id'=>$id],
                    [
                        'cover_letter'=>$lm,
                        'cv'=>$cv,
                        'contract_type'=>$request->contract,
                        'requested_position'=> $request->requested_position,
                        'request_type' => $request->request_type
                    ]
                );

            return response()->json($request_item,200);
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
        $request_item = JobApplication::find($id);
        if (is_null($request_item)){
            return response()->json([
               'message'=> 'Demande d\'emploi introuvable !',
                'data' => null
            ],404);
        } else{
            if ($request_item->delete()){
                RhRepository::deleteUploadFile(public_path('uploads/cv/'.$request_item->cv));
                if ($request_item->cover_letter){
                    RhRepository::deleteUploadFile(public_path('uploads/lm/'.$request_item->cover_letter));
                }
                return response()->json([
                    'data' => null
                ],200);
            }
            return response()->json([
                'message' => 'server error',
            ],500);
        }
    }
}
