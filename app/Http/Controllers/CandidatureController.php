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
                'genre' => 'required|string',
                'school' => 'required|string',
                'cv' => 'required|file|mimes:txt,pdf,doc,docx',
                'lettre' => 'required|file|mimes:txt,pdf,doc,docx',
                'status' => 'required|string'
            ]);
        
            $cvPath = $request->file('cv')->store('cv_files');
            $lettrePath = $request->file('lettre')->store('lettre_files');
        
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
                'genre' => $validatedData['genre'],
                'school' => $validatedData['school'],
                'cv' => $cvPath,
                'lettre' => $lettrePath,
                'status' => $validatedData['status']
            ]);
        
            return response()->json([
                'success' => true,
                'message' => 'Candidature created successfully',
                'data' => $candidature
            ], Response::HTTP_CREATED);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
    
        } catch (\Illuminate\Http\Exceptions\PostTooLargeException $e) {
            return response()->json([
                'success' => false,
                'message' => 'File size too large'
            ], Response::HTTP_REQUEST_ENTITY_TOO_LARGE);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function downloadCV($id)
    {
        try {
            $candidature = Candidature::findOrFail($id);
            $cvPath = $candidature->cv;
    
            if (!Storage::exists($cvPath)) {
                return response()->json([
                    'response' => Response::HTTP_NOT_FOUND,
                    'success' => false,
                    'message' => 'File not found',
                ], Response::HTTP_NOT_FOUND);
            }
    
            return Storage::download($cvPath);
            
        } catch (QueryException $e) {
            return response()->json([
                'response' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'success' => false,
                'message' => $e->getMessage(),
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
    public function downloadLettre($id)
    {
        try {
            $candidature = Candidature::findOrFail($id);
            $lettrePath = $candidature->lettre;
    
            if (!Storage::exists($lettrePath)) {
                return response()->json([
                    'response' => Response::HTTP_NOT_FOUND,
                    'success' => false,
                    'message' => 'File not found',
                ], Response::HTTP_NOT_FOUND);
            }
    
            return Storage::download($lettrePath);
            
        } catch (QueryException $e) {
            return response()->json([
                'response' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    

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

            $candidature->status = $status;
            $candidature->save();

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
    public function show($id)
    {
        try {
            $candidature = Candidature::findOrFail($id);

            return response()->json([
                'response' => Response::HTTP_OK,
                'success' => true,
                'data' => $candidature
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            return response()->json([
                'response' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'success' => false, 
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json([
                'response' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
