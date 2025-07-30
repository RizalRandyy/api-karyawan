<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Http\Requests\DivisionFilterRequest;

class DivisionController extends Controller
{
    public function index(DivisionFilterRequest $request)
    {
        $validated = $request->validated();

        $query = Division::select('id', 'name');

        if (!empty($validated['name'])) {
            $query->where('name', 'like', '%' . $validated['name'] . '%');
        }

        $divisions = $query->paginate(10);

        // Jika data kosong
        if ($divisions->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
                'data' => [
                    'divisions' => [],
                ],
                'pagination' => [
                    'current_page' => $divisions->currentPage(),
                    'last_page' => $divisions->lastPage(),
                    'per_page' => $divisions->perPage(),
                    'total' => $divisions->total(),
                ],
            ], 404);
        }

        // Jika data ada
        return response()->json([
            'status' => 'success',
            'message' => 'Data divisi berhasil diambil',
            'data' => [
                'divisions' => $divisions->items(),
            ],
            'pagination' => [
                'current_page' => $divisions->currentPage(),
                'last_page' => $divisions->lastPage(),
                'per_page' => $divisions->perPage(),
                'total' => $divisions->total(),
            ],
        ], 200);
    }
}
