<?php

namespace App\Http\Controllers;
use App\Models\Administrator;
use App\Models\Role;
use App\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->join('administrators','users.id','=','administrators.user_id')
            ->orderBy('users.created_at','DESC')
            ->paginate(10);
        $roles = Role::query()
            ->join('user_role','roles.id','=','user_role.role_id')
            ->get();
        if (count($users) === 0) {
            return response()->json('No data',404);
        }
        return response()->json([
            'users'=>$users,
            'roles'=>$roles
        ], 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'firstName' => 'required|string|max:255',
            'role' => 'required'
        ]);

        if ($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $user = new User();
        $admin = new Administrator();
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->avatar = "http://localhost:8000/uploads/avatar/user.png";

        if ($user->save()){
            $admin->firstName = $request->firstName;
            $admin->lastName = $request->lastName;
            $admin->user_id = $user->id;
            if ($admin->save()){
                $data = DB::table('users')
                    ->join('administrators', 'users.id', '=', 'administrators.user_id')
                    ->select('users.*','administrators.*')
                    ->where('users.id','=',$user->id)
                    ->get();
              for ($i=0; $i <count($request->role) ; $i++) {
                    UserRole::create([
                        'user_id' => $user->id,
                        'role_id' => $request->role[$i]
                    ]);
                }
                $roles = DB::table('roles')
                ->join('user_role','roles.id','=','user_role.role_id')
                ->select('roles.*')
                ->where('user_role.user_id','=',$user->id)
                ->get();
                return response()->json([
                    'user' => $data,
                    'roles' => $roles
                ],200);
            } else{
                try {
                    $user->delete();
                } catch (\Exception $e) {
                    echo 'erreur :'.$e;
                }
            }
        } else{
            return response()->json(
                'Server error',
                500
            );
        }
    }

    public function show (int $id){
    $data = User::query()
            ->join('administrators','administrators.user_id','=','users.id')
            ->where('users.id','=',$id)
            ->select('administrators.*','users.*')
            ->first();
     return response()->json($data);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required','string','email',Rule::unique('users')->ignore($request->user_id)],
            'password' => ['required','confirmed'],
            'firstName' => ['required'],
            'lastName' => ['required']
        ]);

        if ($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        $user = User::find($request->user_id);
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        if ($user->save()){
            $admin = Administrator::query()->select('*')->where('user_id','=',$request->user_id)->first();
            $admin->firstName = $request->firstName;
            $admin->lastName = $request->lastName;
            $admin->save();

            $data = User::query()->join('administrators','users.id','=','administrators.user_id')
                ->where('users.id','=',$request->user_id);

            return response()->json($data);
        }
    }

    public function destroy(int $id)
    {
        $user = User::find($id);
        $user->confirmed = false;

        if ($user->save()){
            return response()->json($user);
        }
    }

}
