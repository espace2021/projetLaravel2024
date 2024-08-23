<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthController extends Controller
{
        /**
     * Create a new AuthController instance.
     *
     * @return void
     */
   /* public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }*/

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
        
        $input = $request->only('email', 'password');
        $jwt_token = null;
  
        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], Response::HTTP_UNAUTHORIZED);
        }
  
        return response()->json([
            'success' => true,
            'token' => $jwt_token,
        ]);
        
    }

        /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'role' => 'required|string|in:admin,user',  // Role doit être admin ou user
            'avatar' => 'nullable|string', // Avatar optionnel et doit être une chaîne de caractères
        ]);
    
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
    
        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)],
                    ['isActive' => false] // Initialiser isActive à false
                ));
    
        // Envoyer l'email de vérification

        $verificationUrl = route('verify.email', ['email' => $user->email]);
        
        Mail::send([], [], function ($message) use ($user, $verificationUrl) {
            $message->to($user->email)
                    ->subject('Verification de votre email')
                    ->html("<h2>{$user->name}! Merci de vous être inscrit sur notre site</h2>
                            <h4>Veuillez vérifier votre email pour continuer...</h4>
                            <a href='{$verificationUrl}'>Cliquez ici</a>");
        });

    return response()->json([
        'message' => 'User successfully registered. Please verify your email.',
        'user' => $user
    ], 201);
    
        return response()->json([
            'message' => 'User successfully registered. Please verify your email.',
            'user' => $user
        ], 201);
    }

    //Method verify Email

    public function verifyEmail(Request $request) {
        $user = User::where('email', $request->query('email'))->first();
    
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
    
        if ($user->isActive) {
            return response()->json([
                'success' => true,
                'message' => 'Account already activated'
            ]);
        }
    
        $user->isActive = true;
        $user->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Account activated successfully'
        ]);
    }
    
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        $this->guard()->logout();
        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
        ], 200);
    }

    /**
    * Return auth guard
    */
    private function guard()
    {
        return Auth::guard();
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth('api')->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth('api')->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

}
