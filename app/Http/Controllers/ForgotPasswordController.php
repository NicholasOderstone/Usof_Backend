<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use App\Models\PasswordResets;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Http\Controllers\MailController;
class ForgotPasswordController extends Controller
{
    public function forgot(Request $request) {

        $user = User::where('email', $request->input('email'))->first();
        $token = Str::random(10);

        try {
            if (PasswordResets::where('email', $user->email)->first())
                PasswordResets::where('email', $user->email)->update(['token' => $token]);
            else
                PasswordResets::create([
                    'email' => $user->email,
                    'token' => $token
                ]);
            
            $data = [
                'title' => 'Forgot Password reset confirmation',
                'subject' => 'Forgot Password Usof',
                'body' => URL::current() . '/' . $token,
            ];
            // MailController::sendEmail($user->email, $data);
            
            // $data = [
            //     'name' => $user->name,
            //     'resetLink' => URL::current() . '/' . $token,
            //     'removeLink' => URL::current() . '/' . $token . '/remove'
            // ];

            $data = [
                'name' => $user->name,
                'resetLink' => URL::current() . '/' . $token
            ];
            Mail::send('emails.ForgotPass', $data, function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Password reset confirmation');
            });

            return response([
                'message' => 'Password reset confirmation sent to ' . $user->email . '!'
            ]);
            
        } catch (\Exception $exception) {
            return response([
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    public function resetView(Request $request, $token) {
        try {
            if (!$data = PasswordResets::where('token', $token)->first())
                return response([
                    'message' => 'Invalid token!'
                ], 400);

            if (!$user = User::where('email', $data->email)->first())
                return response([
                    'message' => 'User does not exist!'
                ], 404);

        } catch (\Exception $exception) {
            return response([
                'message' => $exception->getMessage()
            ], 400);
        }

        return response()->view('resetPass'); 
    }
    public function reset(Request $request, $token) {
        try {
            if (!$data = PasswordResets::where('token', $token)->first())
                return response([
                    'message' => 'Invalid token!'
                ], 400);

            if (!$user = User::where('email', $data->email)->first())
                return response([
                    'message' => 'User does not exist!'
                ], 404);

            $user->password = bcrypt($request->input('password'));
            $user->save();

            PasswordResets::where('email', $data->email)->delete();
        } catch (\Exception $exception) {
            return response([
                'message' => $exception->getMessage()
            ], 400);
        }

        return response()->view('passChanged', ['name' => $user->name]);
    }
}

