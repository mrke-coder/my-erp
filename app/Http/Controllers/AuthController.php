<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Models\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|string',
        'remember_me'=>'boolean'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){

            $user = $request->user();

            $roles = DB::table('roles')
                ->join('user_role','user_role.role_id','=','roles.id')
                ->where('user_role.user_id','=',$user->id)
                ->get();
            $array_role =[];
            foreach ($roles as $role){
                $array_role[] = $role->role;
            }

            if (in_array('administrator',$array_role)){
                $tokenData = $user->createToken('Personnal Access Token',['do_anyThings']);
            } else{
                $tokenData = $user->createToken('Personnal Access Token',['can_create']);
            }
            $token = $tokenData->token;
            if ($request->remember_me){
                $token->expires_at = Carbon::now()->addYears(1);
            }
            $userData = DB::table('users')
                ->join('administrators','users.id','=','administrators.user_id')
                ->where('users.id','=',$user->id)
                ->get();
            if ($token->save()){
                return response()->json([
                    'user' => $userData,
                    'roles'=>$array_role,
                    'access_token' => $tokenData->accessToken,
                    'token_type' => 'Bearer',
                    'token_scope' => $tokenData->token->scopes[0],
                    'expires_at' => Carbon::parse($tokenData->token->expires_at)->toDateTimeString()
                ],200);
            } else{
                return response()->json('some error',500);
            }
        } else{
            return response()->json([
                'message' => 'unAuthorized, access denied',
                'status' => 401
            ],401);
        }
    }

    public function profil(Request $request)
    {
        if ($request->user()) {
            $data_user = DB::table('users')
                ->join('administrators','users.id','=','administrators.user_id')
                ->where('users.id',$request->user()->id)
                ->first();
            $roles = DB::table('roles')
                ->join('user_role','roles.id','=','user_role.role_id')
                ->where('user_role.user_id','=', $request->user()->id)
                ->get();
            return response()->json([
                'user'=>$data_user,
                'roles'=>$roles
            ],200);
        } else{
            return response()->json([
                'message' => 'No loggedIn',
                'status_code' => 500
            ],500);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json('logout ok',200);
    }
}
