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
use Illuminate\Support\Facades\Mail;
use App\Mail\UserStatusNotification;
use App\Models\Department;

class UserController extends Controller
{

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'prenom' => 'nullable|string',
            'cin' => 'required|integer|unique:users',
            'cnss' => 'required|integer|unique:users',
            'email' => 'required|email|unique:users',
            'date_de_naissance' => 'nullable|date',
            'genre' => 'nullable|string',
            'salaire' => 'nullable|numeric',
            'tel' => 'nullable|integer',
            'adresse' => 'nullable|string',
            'password' => 'required',
            'role' => 'nullable|string',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'response' => Response::HTTP_BAD_REQUEST,
                'success' => false,
                'message' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        } else {
            try {

                $photoPath = $request->file('photo')->store('photos', 'public');
                $user = new User;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->remember_token = Str::random(60);
                $user->photo = $photoPath;
                $user->cin = $request->cin;
                $user->cnss = $request->cnss;
                $user->date_de_naissance = $request->date_de_naissance;
                $user->genre = $request->genre;
                $user->salaire = $request->salaire;
                $user->tel = $request->tel;
                $user->adresse = $request->adresse;
                $user->role = $request->role;
                $user->save();
    
                return response()->json([
                    'response' => Response::HTTP_CREATED,
                    'success' => true,
                    'message' => 'User created successfully',
                    'data' => new UserResource($user)
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
    


    
    public function toggleStatus($id)
    {
        try {
            $user = User::findOrFail($id);
            $newStatus = ($user->status === 'activated') ? 'desactivated' : 'activated';
            $user->status = $newStatus;
            $user->save();
    
            Mail::to($user->email)->send(new UserStatusNotification($user, $newStatus));
    
            return response()->json([
                'response' => Response::HTTP_OK,
                'success' => true,
                'message' => 'User status toggled and email sent successfully',
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
    

        // public function update($id, Request $request)
        // {
        //     $validator = Validator::make($request->all(), [
        //         'name' => 'required',
        //         'prenom' => 'required',
        //         'cin' => 'required',
        //         'cnss' => 'required',
        //         'post' => 'required',
        //         'date_de_naissance' => 'required|date',
        //         'genre' => 'required',
        //         'salaire' => 'required|numeric',
        //         'date_embauche' => 'required|date',
        //         'tel' => 'required',
        //         'ville' => 'required',
        //         'adresse' => 'required',
        //         'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        //         'email' => 'required|email|unique:users,email,' . $id,
        //         'role' => 'required',
        //         'department_id' => 'required|integer|exists:departments,id',
        //     ]);
        
        //     if ($validator->fails()) {
        //         return response()->json([
        //             'response' => Response::HTTP_BAD_REQUEST,
        //             'success' => false,
        //             'message' => $validator->errors(),
        //         ], Response::HTTP_BAD_REQUEST);
        //     } else {
        //         try {
        //             $user = User::findOrFail($id);
        //             $user->name = $request->name;
        //             $user->prenom = $request->prenom;
        //             $user->cin = $request->cin;
        //             $user->cnss = $request->cnss;
        //             $user->post = $request->post;
        //             $user->date_de_naissance = $request->date_de_naissance;
        //             $user->genre = $request->genre;
        //             $user->salaire = $request->salaire;
        //             $user->date_embauche = $request->date_embauche;
        //             $user->tel = $request->tel;
        //             $user->ville = $request->ville;
        //             $user->adresse = $request->adresse;
        //             $user->role = $request->role;
        //             $user->department_id = $request->department_id;
        //             if ($request->hasFile('image')) {
        //                 $imagePath = $request->file('image')->getRealPath();
        //                 $result = Cloudinary::upload($imagePath, ['folder' => 'user']);
        //                 $imageUrl = $result->getSecurePath();
        //                 $user->image = $imageUrl;
        //             }
        
        //             $user->email = $request->email;
        

        //             if ($request->has('password')) {
        //                 $user->password = bcrypt($request->password);
        //             }
        
        //             $user->save();
        
        //             return response()->json([
        //                 'response' => Response::HTTP_OK,
        //                 'success' => true,
        //                 'message' => 'User updated successfully',
        //                 'data' => $user
        //             ], Response::HTTP_OK);
        //         } catch (QueryException $e) {
        //             return response()->json([
        //                 'response' => Response::HTTP_INTERNAL_SERVER_ERROR,
        //                 'success' => false,
        //                 'message' => $e->getMessage(),
        //             ], Response::HTTP_INTERNAL_SERVER_ERROR);
        //         }
        //     }
        // }
        public function update($id, Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'prenom' => 'required',
        'cin' => 'required',
        'cnss' => 'required',
        'date_de_naissance' => 'required|date',
        'genre' => 'required',
        'salaire' => 'required|numeric',
        'tel' => 'required',
        'adresse' => 'required',
        'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        'email' => 'required|email|unique:users,email,' . $id,
        'role' => 'required',
        'department_name' => 'required|string|exists:departments,name',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'response' => Response::HTTP_BAD_REQUEST,
            'success' => false,
            'message' => $validator->errors(),
        ], Response::HTTP_BAD_REQUEST);
    } else {
        try {
            $department = Department::where('name', $request->department_name)->first();

            if (!$department) {
                return response()->json([
                    'response' => Response::HTTP_NOT_FOUND,
                    'success' => false,
                    'message' => 'Department not found',
                ], Response::HTTP_NOT_FOUND);
            }

            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->prenom = $request->prenom;
            $user->cin = $request->cin;
            $user->cnss = $request->cnss;
            $user->date_de_naissance = $request->date_de_naissance;
            $user->genre = $request->genre;
            $user->salaire = $request->salaire;
            $user->tel = $request->tel;
            $user->adresse = $request->adresse;
            $user->role = $request->role;
            $user->department_id = $department->id;

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
        $query = User::query();

        if ($request->has('role')) {
            $query->where('role', 'like', "%" . $request->input('role') . "%");
        }
        if ($request->has('name')) {
            $query->where('name', 'like', "%" . $request->input('name') . "%");
        }
        if ($request->has('prenom')) {
            $query->where('prenom', 'like', "%" . $request->input('prenom') . "%");
        }
        if ($request->has('cin')) {
            $query->where('cin', $request->input('cin'));
        }
        if ($request->has('cnss')) {
            $query->where('cnss', $request->input('cnss'));
        }
        if ($request->has('email')) {
            $query->where('email', 'like', "%" . $request->input('email') . "%");
        }
        if ($request->has('genre')) {
            $query->where('genre', 'like', "%" . $request->input('genre') . "%");
        }

        $users = $query->get();

        return response()->json([
            'response' => Response::HTTP_OK,
            'success' => true,
            'message' => 'Search results',
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
