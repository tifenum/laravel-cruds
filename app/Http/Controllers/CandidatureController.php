<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Mail\CandidatureStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class CandidatureController extends BaseController
{
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'nom' => 'required|string',
                'prenom' => 'required|string',
                'cin' => 'required|string',
                'date_de_naissance' => 'required|date',
                'adresse' => 'required|string',
                'telephone' => 'required|string',
                'diplome' => 'required|string',
                'level_study' => 'required|string',
                'email' => 'required|email',
                'experience' => 'required|string',
                'year_ex' => 'required|integer',
                'school' => 'required|string',
                'cv' => 'required|file|mimes:txt,pdf,doc,docx',
                'lettre' => 'required|string',
                'status' => 'required|string'
            ]);
        
            // Store the uploaded CV file
            $cvPath = $request->file('cv')->store('cv_files');
        
            // Create a new Candidature record
            $candidature = Candidature::create([
                'nom' => $validatedData['nom'],
                'prenom' => $validatedData['prenom'],
                'cin' => $validatedData['cin'],
                'date_de_naissance' => $validatedData['date_de_naissance'],
                'adresse' => $validatedData['adresse'],
                'telephone' => $validatedData['telephone'],
                'diplome' => $validatedData['diplome'],
                'level_study' => $validatedData['level_study'],
                'email' => $validatedData['email'],
                'experience' => $validatedData['experience'],
                'year_ex' => $validatedData['year_ex'],
                'school' => $validatedData['school'],
                'cv' => $cvPath,
                'lettre' => $validatedData['lettre'],
                'status' => $validatedData['status']
            ]);
        
            return response()->json([
                'success' => true,
                'message' => 'Candidature created successfully',
                'data' => $candidature
            ], Response::HTTP_CREATED);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return a response with validation errors
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
    
        } catch (\Illuminate\Http\Exceptions\PostTooLargeException $e) {
            // Handle file size errors
            return response()->json([
                'success' => false,
                'message' => 'File size too large'
            ], Response::HTTP_REQUEST_ENTITY_TOO_LARGE);
    
        } catch (\Exception $e) {
            // Handle other types of errors
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    

    public function index()
    {
        try {
            $candidatures = Candidature::all();

            return response()->json([
                'response' => Response::HTTP_OK,
                'success' => true,
                'data' => $candidatures
            ], Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'response' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // public function updateStatus($id, $status)
    // {
    //     try {
    //         $candidature = Candidature::findOrFail($id);
    //         if (!in_array($status, ['accepted', 'refused'])) {
    //             return response()->json([
    //                 'response' => Response::HTTP_BAD_REQUEST,
    //                 'success' => false,
    //                 'message' => 'Invalid status',
    //             ], Response::HTTP_BAD_REQUEST);
    //         }

    //         $candidature->status = $status;
    //         $candidature->save();

    //         return response()->json([
    //             'response' => Response::HTTP_OK,
    //             'success' => true,
    //             'message' => 'Candidature status updated successfully',
    //             'data' => $candidature
    //         ], Response::HTTP_OK);

    //     } catch (QueryException $e) {
    //         return response()->json([
    //             'response' => Response::HTTP_INTERNAL_SERVER_ERROR,
    //             'success' => false,
    //             'message' => $e->getMessage(),
    //         ], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }
    public function updateStatus($id, $status)
    {
        try {
            $candidature = Candidature::findOrFail($id);
            if (!in_array($status, ['accepted', 'refused'])) {
                return response()->json([
                    'response' => Response::HTTP_BAD_REQUEST,
                    'success' => false,
                    'message' => 'Invalid status',
                ], Response::HTTP_BAD_REQUEST);
            }

            // Update the status
            $candidature->status = $status;
            $candidature->save();

            // Send notification email
            Mail::to($candidature->email)->send(new CandidatureStatusNotification($candidature, $status));

            return response()->json([
                'response' => Response::HTTP_OK,
                'success' => true,
                'message' => 'Candidature status updated successfully',
                'data' => $candidature
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            return response()->json([
                'response' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try {
            $candidature = Candidature::findOrFail($id);
            Storage::delete($candidature->cv);
            $candidature->delete();

            return response()->json([
                'response' => Response::HTTP_OK,
                'success' => true,
                'message' => 'Candidature deleted successfully',
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
