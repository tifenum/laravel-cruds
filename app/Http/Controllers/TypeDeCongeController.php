<?php

namespace App\Http\Controllers;

use App\Models\TypeDeConge;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class TypeDeCongeController extends BaseController
{
    public function index()
    {
        return TypeDeConge::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        return TypeDeConge::create($request->all());
    }

    public function show(TypeDeConge $typeDeConge)
    {
        return $typeDeConge;
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $typeDeConge = TypeDeConge::findOrFail($id);
        $typeDeConge->update($request->all());

        return response()->json([
            'message' => 'Type de conge updated successfully',
            'data' => $typeDeConge,
        ], 200);
    }

    public function destroy($id)
    {
        $typeDeConge = TypeDeConge::findOrFail($id);
        $typeDeConge->delete();

        return response()->noContent();
    }
}
