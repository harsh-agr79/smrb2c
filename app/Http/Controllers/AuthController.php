<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ForgotPassword;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(['token' => $token, 'user'=> $user], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

   public function sendResetLinkEmail(Request $request)
    {
        // Validate email
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        // Create a random token for resetting password
        $token = Str::random(60);

        // Save token in password_resets table
        \DB::table('users')->where('email', $request->email)->update(
            [
                'email_enc' => Hash::make($user->email),
                'token_fp' => Hash::make($token),
                'fp_at' => now()
            ]
        );

        // Send reset password email
        Mail::to($user->email)->send(new ForgotPassword($user, Crypt::encryptString($token), Crypt::encryptString($user->email)));

        return response()->json("Email Has Been Sent", 200);
    }

    public function rp_validateCreds(Request $request){
        $email = Crypt::decryptString($request->email);
        $token = Crypt::decryptString($request->token);

        $user = DB::table('users')->where('email', $email)->first();
        if($user){
            if(Hash::check($token, $user->token_fp) && Hash::check($email, $user->email_enc)){
                return response()->json($request->email, 200);
            }
            else{
                return response()->json("Dont Allow Reset", 400);
            }
        }
        return response()->json("Dont Allow Reset", 400);
    }

    public function set_newpass(Request $request){
        $email = Crypt::decryptString($request->token);
        $password = $request->password;

        $user = DB::table('users')->where('email', $email)->first();
        if(Hash::check($email, $user->email_enc)){
            DB::table('users')->where('email', $email)->update([
                 'password' => Hash::make($request->password),
                 'email_enc'=> Str::random(60),
                 'token_fp'=> Hash::make(Str::random(60)),
                 'fp_at'=> NULL,
            ]);
            return response()->json("Password Changed", 200);
        }
        else{
            return response()->json("Error", 406);
        }
    }
}