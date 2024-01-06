<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'login' => 'required',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'role' => 'string',
            'phone' => 'required|digits_between:10,19',
            'login' => 'required|string|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    public function logout() {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    public function userProfile() {
        return response()->json(auth()->user());
    }

    public function allUserProfile() {
        $users = User::all()->except(Auth::id());
        return response()->json($users);
    }

    public function update(Request $request)
    {
        $edited = User::find($request->id);

        $validator = Validator::make($request->all(), [
            'name' => 'string|between:2,100',
            'role' => 'string',
            'login' => 'string|max:100',
            'password' => 'string|confirmed|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()],401);

        }
        $name = $request->name;
        $role = $request->role;
        $phone = $request->phone;
        $login = $request->login;
        $password = $request->password;

        $edited->name = $name;
        $edited->role = $role;
        $edited->login = $login;
        $edited->phone = $phone;
        $edited->password = bcrypt($password);
        $edited->save();

        return response()->json($edited);
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 6000,
            'user' => auth()->user()
        ]);
    }

    public function checkToken() {
        return response()->json(['success' => true], 200);
    }

    public function destroy($user_id)
    {
        $destroy = User::find($user_id);
        $destroy->delete();
        return response()->json(['200' => 'success']);
    }

    public function logs()
    {
        return Activity::all();
    }
}
