<?php
namespace App\Http\Controllers;
use App\Mail\PasswordEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Inventory;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
//        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()], 422);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'surname' => 'required',
            'email' =>  ['required', 'email', Rule::unique('users', 'email')],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()], 422);
        }

        $password = Str::random(8);

        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($password)],
                    ['isAdmin' => false]
                ));

        Mail::to($request->email)->send(New PasswordEmail($password));

        return response()->json([
            'status' => 'ok',
            'message' => 'User successfully registered',
        ], 201);
    }

    public function logout() {
        auth()->logout();
        return response()->json([
            'status' => 'ok',
            'message' => 'User successfully signed out'], 200);
    }

    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    public function userProfile() {
        return response()->json(auth()->user());
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'exists:users,email']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()], 422);
        }

        $password = Str::random(8);
        $userData = User::whereEmail($request->email)->first();

        $userData->update([
            'password'=>bcrypt($password)
        ]);
        $userData->save();

        Mail::to($request->email)->send(New PasswordEmail($password));

        return response()->json([
            'status' => 'ok',
            'message' => 'Reset password successfully',
        ], 200);

    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()], 422);
        }

        $userData = User::whereEmail(auth()->user()->email)->first();
        $userData->update([
            'password'=>bcrypt($request->password)
        ]);
        $userData->save();

        return response()->json([
            'status' => 'ok',
            'message' => 'Password successfully updated',
        ], 200);

    }

    public function delete($email)
    {
        $user = User::whereEmail($email)->first();
        if($user == null)
        {
            return response()->json([
                'status'  => 'error',
                'message' => 'User does not exist'
            ], 422);
        }

        $inventory = Inventory::where('user_id', $user['id'])->first();
        if($inventory != null)
        {
            return response()->json([
                'status'  => 'error',
                'message' => 'The student has taken inventory and not give up'
            ], 400);
        }

        $user->delete();

        return response()->json([
            'status'  => 'ok',
            'message' => 'Student successfully deleted',
        ], 200);

    }
}
