<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Models\Role;
use App\Models\UserRole;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Repositories\RhRepository;
use Illuminate\Validation\Rule;
class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|string'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        if(!Auth::attempt(['email'=>$request->email,'password'=>$request->password])) {
            return response()->json('unAuthorized, access denied', 401);
        }

        $user = $request->user();

        $role = DB::table('roles')
            ->join('user_role','user_role.role_id','=','roles.id')
            ->where('user_role.user_id','=',$user->id)
            ->first();

        $tokenData =null;
        $array_role = [];

        switch ($role->role){
            case 'administrator':
                $tokenData = $user->createToken('Personnal Access Token',['do_anyThings']);
                $array_role[] = 'admin';
                break;
            case 'human resource':
                $tokenData = $user->createToken('Personnal Access Token', ['Rh']);
                $array_role[] = 'Rh';
                break;
            default:
                return response()->json('Rôle non pris en compte', 401);
        }


        $rolesU = UserRole::query()
            ->where('user_id','=',$user->id)
            ->get();

        foreach ($rolesU as $v) {
            $array_role[] = $v->clearances;
        }


        $token = $tokenData->token;
        $userData = DB::table('users')
            ->join('administrators','users.id','=','administrators.user_id')
            ->where('users.id','=',$user->id)
            ->first();

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
                ->select('roles.*', 'user_role.clearances as clearances')
                ->where('user_role.user_id','=', $request->user()->id)
                ->first();

            return response()->json([
                'user'=>$data_user,
                'roles'=>$roles,
                'clearances' => explode(',',$roles->clearances)
            ],200);

        } else{
            return response()->json('No loggedIn',403);
        }
    }

    public function editAvatar (Request $request, $id) {

        $validator = Validator::make($request->all(), [
            'avatar' => ['required', 'image', 'mimes:png,jpg']
        ]);


        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        dd($request->id);

        $file = RhRepository::uploadFile($request->file('avatar'), 'avatar');

        $user = User::find($request->id);

    }

    public function editPassword (Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'password' => ['required','min:8'],
            'new_password' => ['required','min:8','confirmed']
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }



        $user = User::find($id);

        if(!Hash::check($request->password, $user->password)){
            return response()->json('Le mot de passe actuel ne correspond à nos enrégistrement',404);
        }

        if(Hash::check($request->new_password, $user->password)){
            return response()->json('Désolé, vous devez absolument choisi un nouveau mot de passe différent du mot de passe actuel',404);
        }

        $user->password = Hash::make($request->new_password);

        if($user->save()){
            return response()->json($user);
        }
    }

    public function editUsername (Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', Rule::unique('users')->ignore($id)],
            'password' => ['required']
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $user = User::find($id);

        if(!Hash::check($request->password, $user->password)){
            return response()->json('Mot de passe incorrect, rééssayez !', 404);
        }

        $user->email = $request->email;

        if($user->save()){
            return response()->json($user);
        }
    }

    public function editAdmin (Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'firstName' =>  ['required', 'string'],
            'lastName' => ['required', 'string']
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $admin = Administrator::find($request->user()->id);

        $admin->firstName = $request->firstName;
        $admin->lastName = $request->lastName;
        $admin->capacity = $request->capacity;
        $admin->hobby = $request->hobby;
        $admin->bio = $request->bio;

        if($admin->save()){
            return response()->json($admin);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json('logout ok',200);
    }
}
