<?php

namespace App\Http\Controllers;

use App\Models\DepartmentType;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class DepartmentTypeController extends BaseController
{
    public function index(Request $request)
    {
        $query = DepartmentType::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        return $query->get();
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        return DepartmentType::create($request->all());
    }

    public function show(DepartmentType $departmentType)
    {
        return $departmentType;
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required']);
        
        $departmentType = DepartmentType::findOrFail($id);
        $departmentType->update($request->all());
    
        return response()->json([
            'message' => 'Department type updated successfully',
            'data' => $departmentType
        ], 200);
    }

    public function destroy($id)
    {
        $departmentType = DepartmentType::findOrFail($id);
        $departmentType->delete();
    
        return response()->noContent();
    }
}
