<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class DepartmentController extends BaseController
{
    public function index(Request $request)
    {
        $query = Department::with('departmentType');

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }


        return $query->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'department_type_id' => 'required|exists:department_types,id',
        ]);
        return Department::create($request->all());
    }

    public function show(Department $department)
    {
        return $department->load('departmentType');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department_type_id' => 'required|exists:department_types,id',
        ]);

        $department = Department::findOrFail($id);
        $department->update($request->all());

        return response()->json([
            'response' => Response::HTTP_OK,
            'success' => true,
            'message' => 'Department updated successfully',
            'data' => $department,
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return response()->json([
            'response' => Response::HTTP_NO_CONTENT,
            'success' => true,
            'message' => 'Department deleted successfully',
        ], Response::HTTP_NO_CONTENT);
    }
}
