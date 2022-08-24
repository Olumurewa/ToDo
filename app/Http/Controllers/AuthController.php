<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'email' => 'required|email',
            'password' => 'required',

        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors()); 
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;
        return $this->sendResponse($success, 'User registered successfully.');
    }
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */

    public function login(Request $request)

    { $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required',

        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors()); 
        }
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('TodoApp')->plainTextToken; 
            $success['Data'] =  $user;
            return $this->sendResponse($success, 'User login successful.');
        } else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 

    }

    public function index(){
        $user = User::all();
        return response()->json([
            $user
        ], 201);
    }
    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'email' => 'email',
            'password' => 'string',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors()); 
        }

        if (User::where('id',$id)->exists()){;
            $user = User::find($id);
            $user->update($request->all());
            $user -> save();
            return response() -> json([
                'message'=>'User Detail Updated.'
            ],201);
        }else{
            return response() -> json([
               'message'=>'User Does Not Exist.' 
            ],404);
        }
    }
    public function show($id){
        $user = User::find($id);
        if (!empty($user)){
            return response()->json($user);
        }else{
            return response() -> json([
                'message'=>'User not found.' 
             ],404);
        }
    }
    public function destroy($id){
        if(User::where('id', $id)->exists()){
            $user=User::find($id);
            $user->delete();
            return response()->json([
                'message'=> 'User Deleted.'
            ],202);
        }else{
            return response() -> json([
                'message'=>'Failure! User Not Found.' 
             ],404);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return [
            'message' => 'user logged out'
        ];

    }
}
