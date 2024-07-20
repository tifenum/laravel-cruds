<?php

namespace App\Http\Controllers;

use App\Models\ContractType;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ContractTypeController extends BaseController
{
    public function index(Request $request)
    {
        $query = ContractType::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        return $query->get();
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        return ContractType::create($request->all());
    }

    public function show(ContractType $contractType)
    {
        return $contractType;
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required']);
        $ContractType = ContractType::findOrFail($id);

        $ContractType->update($request->all());
        
        return response()->json([
            'message' => 'Contract type updated successfully',
            'data' => $ContractType
        ], 200);
    }

    public function destroy($id)
    {
        $ContractType = ContractType::findOrFail($id);
        $ContractType->delete();
        
        return response()->noContent();
    }
}
