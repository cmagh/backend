<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Message;
use App\Http\Resources\ErrorCollection;
use App\Errors\Errors;
use App\Errors\Error;
use Validator;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request) {

        $validatedData = Validator::make($request->all(), ['email' => 'required|email']);

        if($validatedData->fails()){
            foreach ($validatedData->errors()->messages() as $err) {
                Errors::setError(new Error($request->fullUrl(), $err[0], '400'));                
            }          
            return ErrorCollection::make(Errors::getErrors())->response()->setStatusCode(400);
        }

        $response = Password::sendResetLink($request->only('email')); 

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return response()->json([
                    'data' => [
                        'success' => 'Reset password link sent on your email id.'
                    ]
                ] ,200);
                
            case Password::INVALID_USER:
                Errors::setError(new Error(Request()->fullUrl(), 'Email not found', '400'));                    
                return ErrorCollection::make(Errors::getErrors())->response()->setStatusCode(400);                

            default:
                Errors::setError(new Error(Request()->fullUrl(), 'Email just sent', '400'));                    
                return ErrorCollection::make(Errors::getErrors())->response()->setStatusCode(400);
        }
        
    }

    
    public function passwordReset(Request $request){
        $validatedData = Validator::make($request->all(), [
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);    

        if($validatedData->fails()){
            foreach ($validatedData->errors()->messages() as $err) {
                Errors::setError(new Error($request->fullUrl(), $err[0], '400'));                
            }          
            return ErrorCollection::make(Errors::getErrors())->response()->setStatusCode(400);
        }
        

        $resetPasswordStatus = Password::reset($request->all(), function ($user, $password) {
            $user->password = $password;
            $user->save();
        });

        if ($resetPasswordStatus == Password::INVALID_TOKEN) {
            Errors::setError(new Error(Request()->fullUrl(), 'Invalid token', '400'));                    
            return ErrorCollection::make(Errors::getErrors())->response()->setStatusCode(400);            
        }

        return response()->json([
            'data' => [
                'success' => 'Password has been successfully changed.'
            ]
        ] ,200);    
    }
}
