<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class UserController extends Controller
{

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response' => Response::HTTP_BAD_REQUEST,
                'success' => false,
                'message' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }else{
            try {
                $imagePath = $request->file('photo')->getRealPath();
                $result = Cloudinary::upload($imagePath,  ['folder' => 'user']);
                $imageUrl = $result->getSecurePath();
                $user = new User;
                $user->name  = $request->name;
                $user->email  = $request->email;
                $user->password  = Hash::make($request->password);
                $user->remember_token  = Str::random(60);
                $user->photo  = $imageUrl;
                $user->save();
                return response()->json([
                    'response' => Response::HTTP_CREATED,
                    'success' => true,
                    'message' => 'Create user',
                    'data' => $request->all()
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


    
    public function read()
    {
        try {
            $users = User::with(['department', 'contracts', 'conges'])->get();
            return response()->json([
                'response' => Response::HTTP_OK,
                'success' => true,
                'message' => 'Read all users',
                'data' => UserResource::collection($users)
            ], Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'response' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    

    
    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'prenom' => 'required',
            'cin' => 'required',
            'cnss' => 'required',
            'post' => 'required',
            'date_de_naissance' => 'required|date',
            'genre' => 'required',
            'salaire' => 'required|numeric',
            'date_embauche' => 'required|date',
            'tel' => 'required',
            'ville' => 'required',
            'adresse' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'response' => Response::HTTP_BAD_REQUEST,
                'success' => false,
                'message' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        } else {
            try {
                $user = User::findOrFail($id);
                $user->name = $request->name;
                $user->prenom = $request->prenom;
                $user->cin = $request->cin;
                $user->cnss = $request->cnss;
                $user->post = $request->post;
                $user->date_de_naissance = $request->date_de_naissance;
                $user->genre = $request->genre;
                $user->salaire = $request->salaire;
                $user->date_embauche = $request->date_embauche;
                $user->tel = $request->tel;
                $user->ville = $request->ville;
                $user->adresse = $request->adresse;
                $user->role = $request->role;
    
                if ($request->hasFile('image')) {
                    $imagePath = $request->file('image')->getRealPath();
                    $result = Cloudinary::upload($imagePath, ['folder' => 'user']);
                    $imageUrl = $result->getSecurePath();
                    $user->image = $imageUrl;
                }
    
                $user->email = $request->email;
    

                if ($request->has('password')) {
                    $user->password = bcrypt($request->password);
                }
    
                $user->save();
    
                return response()->json([
                    'response' => Response::HTTP_OK,
                    'success' => true,
                    'message' => 'User updated successfully',
                    'data' => $user
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
    public function delete($id)
    {
        try {
            User::destroy($id);
            return response()->json([
                'response' => Response::HTTP_OK,
                'success' => true,
                'message' => 'Delete user by id ' . $id,
            ], Response::HTTP_OK);
            
        } catch (QueryException $e) {
            return response()->json([
                'response' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    
    public function search(Request $request)
    {
        try {
            $keyword = $request->input('keyword');
    
            $users = User::where(function($query) use ($keyword) {
                $query->where('name', 'like', "%$keyword%")
                    ->orWhere('prenom', 'like', "%$keyword%")
                    ->orWhere('cin', 'like', "%$keyword%")
                    ->orWhere('cnss', 'like', "%$keyword%")
                    ->orWhere('post', 'like', "%$keyword%")
                    ->orWhere('date_de_naissance', 'like', "%$keyword%")
                    ->orWhere('genre', 'like', "%$keyword%")
                    ->orWhere('salaire', 'like', "%$keyword%")
                    ->orWhere('date_embauche', 'like', "%$keyword%")
                    ->orWhere('tel', 'like', "%$keyword%")
                    ->orWhere('ville', 'like', "%$keyword%")
                    ->orWhere('adresse', 'like', "%$keyword%")
                    ->orWhere('image', 'like', "%$keyword%")
                    ->orWhere('email', 'like', "%$keyword%")
                    ->orWhere('role', 'like', "%$keyword%");
            })->get();
    
            return response()->json([
                'response' => Response::HTTP_OK,
                'success' => true,
                'message' => 'Search results for keyword: ' . $keyword,
                'data' => UserResource::collection($users)
            ], Response::HTTP_OK);
            
        } catch (QueryException $e) {
            return response()->json([
                'response' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function paginate(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $respons = User::paginate($perPage);
            return response()->json([
                'response' => Response::HTTP_OK,
                'success' => true,
                'message' => 'Read user with pagination page ' . $request->page,
                'data' => UserResource::collection($respons)
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