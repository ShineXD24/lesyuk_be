<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AuthenticationController extends Controller
{
    public function register(Request $request){
        try{
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|max:20',
            // 'no_hp' => 'required|digits:10|unique:users',
        ]);
        // $token = getenv("TWILIO_AUTH_TOKEN");
        // $twilio_sid = getenv("TWILIO_SID");
        // $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        // $twilio = new Client($twilio_sid, $token);
        // $twilio->verify->v2->services($twilio_verify_sid)
        //     ->verifications
        //     ->create($data['no_hp'], "sms");

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
            // 'no_hp' => $request->no_hp,
        ]);
        // return redirect()->route('verify')->with(['no_hp' => $data['no_hp']]);

        
           //$user->sendEmailVerificationNotification();

           // return redirect()->to('/verify-view')->with('success', 'Email verifikasi telah dikirim!');
            return response()->json([
                'user' => $user,
                'message' => 'Berhasil Daftar!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal Daftar!',
                'error' => $e->getMessage()
            ], 409);
        }
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:20',
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user){
            return response()->json([
                'message' => 'Email tidak terdaftar!'
            ], 401);
        }

        if(!Hash::check($request->password, $user->password)){
            return response()->json([
                'message' => 'Password salah!'
            ], 401);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        $response = [
            'message' => 'Berhasil Login!',
            'user' => $user,
            'token' => $token
        ];
        // return redirect()->to('/')->with('success', 'Berhasil Login!');
        return response()->json($response, 200);
    }
    public function dashboard()
    {
        if(Auth::check()){
            return view('dashboard');
        }
        
        return redirect("login")->withSuccess('You are not allowed to access');
        // return redirect("register")->withSuccess('You are not allowed to access');
        
    }
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Berhasil Logout !'
        ], 200);
    }
}