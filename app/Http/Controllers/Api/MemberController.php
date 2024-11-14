<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class MemberController extends Controller
{
    protected $user;
    protected $request;

    public function __construct(
        User $user,
        Request $request
    ){
        $this->user = $user;
        $this->request = $request;
    }

    public function register(Request $request)
    {
        $response = [
            'status' => true,
            'message' => 'error.'
        ];

        $params = $request->json()->all();

        $validator = \Validator::make($params, 
            [
                'name' => 'required',
                'username' => 'required',
                'password' => 'required|confirmed',
            
            ],
            [
                'username.unique' => 'ซ้ำ !! Username นี้มีผู้ใช้แล้ว',
            ]
        );

        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => $validator->error()
            ];

            return response()->json($response);
        }

        $this->user->name = $params['name'];
        $this->user->username = $params['username'];
        $this->user->password = Hash::make($params['password']);

        if($this->user->save()){
            $response = [
                'status' => true,
                'message' => "Success.",
                'result' => $this->user
            ];

            return response()->json($response);
        }

        return response()->json($response);
    }

    /**
     * 
     * 
     */
    public function login(Request $request)
    {
        $response = [
            'status' => true,
            'message' => 'success.'
        ];

        $params = $request->json()->all();

        $member = $this->user
            ->where('username', $params['username'])
            ->first();

        if(!$member){
            $response = [
                'status' => false,
                'message' => "ไม่พบบัญชีผู้ใช้"
            ];

            return response()->json($response);
        }

        if( !Hash::check($params['password'], $member->password)){
            $response = [
                'status' => false,
                'message' => "รหัสผ่านไม่ถูกต้อง"
            ];

            return response()->json($response);
        }

        $field = filter_var($params['username'], FILTER_VALIDATE_EMAIL) ? 'username' : 'username';
        $password = $params['password'];

        if (Auth::attempt([$field => $params['username'], 'password' => $password], true)) {

            // update last active in users table
            $token = $member->createToken('myToken')->plainTextToken;
            
        }

        // $token = $member->createToken('myToken')->plainTextToken;
        
        $response['data'] = $member;
        $response['token'] = $token;

        return response()->json($response);
    }

    /**
     * 
     * 
     */
    public function profile(Request $request)
    {
        $response = [
            'status' => true,
            'message' => "Success."
        ];

        $member = Auth::user();

        $response['user'] = $member;

        return response()->json($response);
    }

    public function logout(Request $request)
    {
        $response = [
            'status' => true,
            'message' => "Success"
        ];
        
        Auth::logout();
    
        return response()->json($response);
    }
}
