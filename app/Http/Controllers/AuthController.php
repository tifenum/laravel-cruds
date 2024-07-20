<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'forget' , 'reset']]);
    }

    // public function register(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required',
    //         'prenom' => 'nullable|string',
    //         'cin' => 'required|integer|unique:users',
    //         'cnss' => 'required|integer|unique:users',
    //         'post' => 'nullable|string',
    //         'email' => 'required|email|unique:users',
    //         'date_de_naissance' => 'nullable|date',
    //         'genre' => 'nullable|string',
    //         'salaire' => 'nullable|numeric',
    //         'date_embauche' => 'nullable|date',
    //         'tel' => 'nullable|integer',
    //         'ville' => 'nullable|string',
    //         'adresse' => 'nullable|string',
    //         'image' => 'nullable|string',
    //         'password' => 'required',
    //         'role' => 'nullable|string',
    //     ]);
    
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'response' => Response::HTTP_BAD_REQUEST,
    //             'success' => false,
    //             'message' => $validator->errors(),
    //         ], Response::HTTP_BAD_REQUEST);
    //     } else {
    //         try {
    //             $user = new User;
    //             $user->name = $request->name;
    //             $user->prenom = $request->prenom;
    //             $user->cin = $request->cin;
    //             $user->cnss = $request->cnss;
    //             $user->post = $request->post;
    //             $user->email = $request->email;
    //             $user->date_de_naissance = $request->date_de_naissance;
    //             $user->genre = $request->genre;
    //             $user->salaire = $request->salaire;
    //             $user->date_embauche = $request->date_embauche;
    //             $user->tel = $request->tel;
    //             $user->ville = $request->ville;
    //             $user->adresse = $request->adresse;
    //             $user->image = $request->image ?? 'profile.jpg';
    //             $user->role = $request->role ?? 'EMPLOYER';
    //             $user->password = Hash::make($request->password);
    //             $user->remember_token = Str::random(60);
    //             $user->save();
    
    //             return response()->json([
    //                 'response' => Response::HTTP_CREATED,
    //                 'success' => true,
    //                 'message' => 'Register successfully.',
    //                 'data' => $request->all()
    //             ], Response::HTTP_CREATED);
    
    //         } catch (QueryException $e) {
    //             return response()->json([
    //                 'response' => Response::HTTP_INTERNAL_SERVER_ERROR,
    //                 'success' => false,
    //                 'message' => $e->getMessage(),
    //             ], Response::HTTP_INTERNAL_SERVER_ERROR);
    //         }
    //     }
    // }
    
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'prenom' => 'nullable|string',
            'cin' => 'required|integer|unique:users',
            'cnss' => 'required|integer|unique:users',
            'post' => 'nullable|string',
            'email' => 'required|email|unique:users',
            'date_de_naissance' => 'nullable|date',
            'genre' => 'nullable|string',
            'salaire' => 'nullable|numeric',
            'date_embauche' => 'nullable|date',
            'tel' => 'nullable|integer',
            'ville' => 'nullable|string',
            'adresse' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'required',
            'role' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'response' => Response::HTTP_BAD_REQUEST,
                'success' => false,
                'message' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        } else {
            try {
                $user = new User;
    
                // Handle photo upload
                if ($request->hasFile('image')) {
                    $photoPath = $request->file('image')->store('photos', 'public');
                    $user->image = $photoPath;
                } else {
                    $user->image = 'profile.jpg'; // Default image if none is provided
                }
    
                $user->name = $request->name;
                $user->prenom = $request->prenom;
                $user->cin = $request->cin;
                $user->cnss = $request->cnss;
                $user->post = $request->post;
                $user->email = $request->email;
                $user->date_de_naissance = $request->date_de_naissance;
                $user->genre = $request->genre;
                $user->salaire = $request->salaire;
                $user->date_embauche = $request->date_embauche;
                $user->tel = $request->tel;
                $user->ville = $request->ville;
                $user->adresse = $request->adresse;
                $user->role = $request->role ?? 'EMPLOYER';
                $user->password = Hash::make($request->password);
                $user->remember_token = Str::random(60);
                $user->save();
    
                return response()->json([
                    'response' => Response::HTTP_CREATED,
                    'success' => true,
                    'message' => 'Register successfully.',
                    'data' => $user
                ], Response::HTTP_CREATED);
    
            } catch (QueryException $e) {
                return response()->json([
                    'response' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'success' => false,
                    'message' => $e->getMessage(),
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }
    
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response' => Response::HTTP_BAD_REQUEST,
                'success' => false,
                'message' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }else{
            try {
                if (! $token = auth()->attempt($request->all())) {
                    return response()->json([
                        'response' => Response::HTTP_UNAUTHORIZED,
                        'success' => false,
                        'message' => 'Username or password wrong',
                    ], Response::HTTP_UNAUTHORIZED);
                }
                return $this->respondWithToken($token);
            } catch (QueryException $e) {
                return response()->json([
                    'response' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'success' => false,
                    'message' => $e->getMessage(),
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }        
    }
    public function me()
    {
        return response()->json([
            'response' => Response::HTTP_OK,
            'success' => true,
            'message' => 'Get information user login.',
            'data' => auth()->user()
        ], Response::HTTP_OK);
    }
    public function forget(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'response' => Response::HTTP_BAD_REQUEST,
                'success' => false,
                'message' => 'email is not registered',
            ], Response::HTTP_BAD_REQUEST);
        }else{
            try {
                $token = Str::random(32);
                DB::table('password_resets')->insert([
                    'email' => $request->email, 
                    'token' => $token, 
                ]); 
                Mail::send('forgetPassword', ['token' => $token], function($message) use($request){
                    $message->to($request->email);
                    $message->subject('Reset Password');
                });
                return response()->json([
                    'response' => Response::HTTP_OK,
                    'success' => true,
                    'message' => 'email sent successfully',
                ], Response::HTTP_OK);
                
            } catch (QueryException $e) {
                return response()->json([
                    'response' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'success' => false,
                    'message' => $e->getMessage(),
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public function logout()
    {
        auth()->logout();
        return response()->json([
            'response' => Response::HTTP_OK,
            'success' => true,
            'message' => 'Successfully logged out',
        ], Response::HTTP_OK);    
    }

    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'response' => Response::HTTP_OK,
            'success' => true,
            'message' => 'JWT Token refresh Successfully',
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::factory()->getTTL() * 60
            ]
        ], Response::HTTP_OK);
    }
}
