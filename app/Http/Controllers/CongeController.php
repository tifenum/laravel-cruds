<?php

namespace App\Http\Controllers;

use App\Models\Conge;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class CongeController extends BaseController
{
    public function index()
    {
        return Conge::with(['typeDeConge', 'user'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'type_de_conge_id' => 'required|exists:type_de_conges,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            'etat' => 'required|in:not studied,refused,accepted',
            'user_id' => 'required|exists:users,id',
        ]);

        return Conge::create($request->all());
    }

    public function show($id)
    {
        $conge = Conge::with(['typeDeConge', 'user'])->findOrFail($id);

        return response()->json($conge);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type_de_conge_id' => 'required|exists:type_de_conges,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            'etat' => 'required|in:not studied,refused,accepted',
            'user_id' => 'required|exists:users,id',
        ]);

        $conge = Conge::findOrFail($id);
        $conge->update($request->all());

        return response()->json([
            'message' => 'Conge updated successfully',
            'data' => $conge,
        ], 200);
    }

    public function destroy($id)
    {
        $conge = Conge::findOrFail($id);
        $conge->delete();

        return response()->json([
            'message' => 'Conge deleted successfully',
        ], 204);
    }

    public function accepterConge($id)
    {
        $conge = Conge::findOrFail($id);
        $conge->update(['etat' => 'accepted']);

        return response()->json([
            'message' => 'Conge accepted',
            'data' => $conge,
        ], 200);
    }

    public function refuserConge($id)
    {
        $conge = Conge::findOrFail($id);
        $conge->update(['etat' => 'refused']);

        return response()->json([
            'message' => 'Conge refused',
            'data' => $conge,
        ], 200);
    }
}
