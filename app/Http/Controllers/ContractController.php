<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ContractController extends BaseController
{
    public function show(Contract $contract)
    {
        return $contract->load(['contractType', 'user']);
    }

    public function index(Request $request)
    {
        $query = Contract::with(['contractType', 'user']);

        if ($request->has('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->has('contract_type_id')) {
            $query->where('contract_type_id', $request->input('contract_type_id'));
        }

        if ($request->has('start_date')) {
            $query->where('start_date', $request->input('start_date'));
        }

        if ($request->has('end_date')) {
            $query->where('end_date', $request->input('end_date'));
        }

        return $query->get();
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'contract_type_id' => 'required|exists:contract_types,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'contract_file' => 'required|file|mimes:pdf,doc,docx'
            ]);
    
            if ($request->hasFile('contract_file')) {
                $filePath = $request->file('contract_file')->store('contract_files');
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No file was uploaded'
                ], Response::HTTP_BAD_REQUEST);
            }
    

            $contract = Contract::create([
                'user_id' => $validatedData['user_id'],
                'contract_type_id' => $validatedData['contract_type_id'],
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
                'contract_file' => $filePath
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Contract created successfully',
                'data' => $contract
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
    
    

    public function getContractFile($id)
    {
        $contract = Contract::findOrFail($id);
    
        if ($contract->contract_file) {
            $filePath = $contract->contract_file;
    
            if (Storage::disk('public')->exists($filePath)) {
                return Storage::disk('public')->download($filePath);
            }
    
            return response()->json(['message' => 'File not found'], 404);
        }
    
        return response()->json(['message' => 'No file associated with this contract'], 404);
    }
    
    public function update(Request $request, $id)
{
    try {
        \Log::info('Request Data:', $request->all());

        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'contract_type_id' => 'required|exists:contract_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'contract_file' => 'nullable|file|mimes:pdf,doc,docx,txt'
        ]);

        $contract = Contract::findOrFail($id);

        if ($request->hasFile('contract_file')) {
            if ($contract->contract_file) {
                if (!Storage::disk('public')->exists($contract->contract_file)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Old file not found',
                    ], 404);
                }

                Storage::disk('public')->delete($contract->contract_file);
            }

            $file = $request->file('contract_file');
            $filePath = $file->store('contract_files', 'public');

            if (!$filePath) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload file',
                ], 500);
            }

            $contract->contract_file = $filePath;
        }

        $contract->update($request->except('contract_file'));

        return response()->json([
            'success' => true,
            'message' => 'Contract updated successfully',
            'data' => $contract,
        ], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $e->errors()
        ], 422);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred: ' . $e->getMessage()
        ], 500);
    }
}

public function downloadContract($id)
{
    try {
        $contract = Contract::findOrFail($id);

        $contractFile = $contract->contract_file;
        
        if (!$contractFile) {
            return response()->json([
                'response' => Response::HTTP_NOT_FOUND,
                'success' => false,
                'message' => 'No file associated with this contract',
            ], Response::HTTP_NOT_FOUND);
        }

        if (!Storage::exists($contractFile)) {
            return response()->json([
                'response' => Response::HTTP_NOT_FOUND,
                'success' => false,
                'message' => 'File not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return Storage::download($contractFile);
        
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
        $contract = Contract::findOrFail($id);
        $contract->delete();

        return response()->json([
            'message' => 'Contract deleted successfully',
        ], 204);
    }
}
