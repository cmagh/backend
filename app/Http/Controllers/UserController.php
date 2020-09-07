<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth; 
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Http\Resources\ErrorCollection;
use App\Errors\Errors;
use App\Errors\Error;
use Validator;

class UserController extends Controller
{

    public function index(User $user)
    {       
        $page = isset(request('page')['number']) ? (int) request('page')['number']: 1;
        $size = isset(request('page')['size']) ? (int) request('page')['size']: 20;      
        $filter = isset(request('filter')['search']) ? request('filter')['search'] : '';
        
        $usersList = $user::when($filter !== '', function ($query, $value) use ($filter){
                            return $query->where('username', 'like', '%'.$filter.'%')->orWhere('email', 'like', '%'.$filter.'%');
                        })->paginate($size, ['*'], 'page[number]', $page);

        if(count($usersList->items()) == 0){
            Errors::setError(new Error(Request()->fullUrl()));            
            return ErrorCollection::make(Errors::getErrors())->response()->setStatusCode(404); 
        }                 

        return UserCollection::make($usersList);
    }
    
    
    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'email' => 'required|unique:users|email|max:50',
            'username' => 'required|unique:users|max:25',
            'password' => 'required|confirmed',
            'phone' => 'required|numeric|digits_between:8,15',
            'birthday' => 'required|date_format:Y-m-d'
        ]);

        if($validatedData->fails()){
            foreach ($validatedData->errors()->messages() as $err) {
                Errors::setError(new Error($request->fullUrl(), $err[0], '400'));                
            }          
            return ErrorCollection::make(Errors::getErrors())->response()->setStatusCode(400);
        }

        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $user = User::create($input); 
        $user->token = $user->createToken('MyApp')->accessToken; 
        
        return UserResource::make($user)->response()->setStatusCode(201);
    }

    public function show($id)
    {
        $user = User::find($id);
        if( ! $user ){
            Errors::setError(new Error(Request()->fullUrl()));            
            return ErrorCollection::make(Errors::getErrors())->response()->setStatusCode(404);            
        }
        return UserResource::make($user);        
    }


    public function update(Request $request, $id)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'phone' => 'required|numeric|digits_between:8,15',
            'birthday' => 'required|date_format:Y-m-d'
        ]);

        if($validatedData->fails()){
            foreach ($validatedData->errors()->messages() as $err) {
                Errors::setError(new Error($request->fullUrl(), $err[0], '400'));                
            }          
            return ErrorCollection::make(Errors::getErrors())->response()->setStatusCode(400);            
        }       
        
        $user = User::find($id);
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->birthday = $request->birthday;        
        $user->save();

        return UserResource::make($user); 
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if( ! $user ){
            Errors::setError(new Error(Request()->fullUrl()));                    
            return ErrorCollection::make(Errors::getErrors())->response()->setStatusCode(404);
        }
        $user->delete();
        return response()->json()->setStatusCode(204);
    }

    public function login(Request $request){
        $validatedData = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validatedData->fails()){
            foreach ($validatedData->errors()->messages() as $err) {
                Errors::setError(new Error(route('user.login'), $err[0], '400'));                
            }          
            return ErrorCollection::make(Errors::getErrors())->response()->setStatusCode(400);
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user();            
            $response = [            
                'type' => 'users',
                'id' => Auth::id(),
                'attributes' => [
                    'token' => $user->createToken(config('app.name'))->accessToken    
                ], 
                'links' => [
                    'self' => route('user.login') 
                ]                
            ];
            return response()->json($response, 200); 
        } 

        Errors::setError(new Error(route('user.login'), 'Unauthorised', '401'));
        return ErrorCollection::make(Errors::getErrors())->response()->setStatusCode(401);       
    }

 
    public function logout(Request $request){
        Auth::guard('api')->user()->token()->revoke();
        return response()->json()->setStatusCode(204);
    }
    
}
