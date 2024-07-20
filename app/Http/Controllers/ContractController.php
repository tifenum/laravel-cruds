<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ContractController extends BaseController
{
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
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'contract_type_id' => 'required|exists:contract_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        return Contract::create($request->all());
    }

    public function show(Contract $contract)
    {
        return $contract->load(['contractType', 'user']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'contract_type_id' => 'required|exists:contract_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $contract = Contract::findOrFail($id);
        $contract->update($request->all());

        return response()->json([
            'message' => 'Contract updated successfully',
            'data' => $contract,
        ], 200);
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
