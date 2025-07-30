<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\EmployeeFilterRequest;
use App\Http\Requests\UpdateEmployeeRequest;

class EmployeeController extends Controller
{
    public function index(EmployeeFilterRequest $request)
    {
        $validated = $request->validated();

        $query = Employee::with('division');

        if (!empty($validated['name'])) {
            $query->where('name', 'like', '%' . $validated['name'] . '%');
        }

        if (!empty($validated['division_id'])) {
            $query->where('division_id', $validated['division_id']);
        }

        $employees = $query->paginate(10);

        if ($employees->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
                'data' => [
                    'employees' => [],
                ],
                'pagination' => [
                    'current_page' => $employees->currentPage(),
                    'per_page' => $employees->perPage(),
                    'total' => $employees->total(),
                    'last_page' => $employees->lastPage(),
                ],
            ], 404);
        } else {
            // Mapping manual untuk menghilangkan division_id, created_at, updated_at
            $mappedEmployees = collect($employees->items())->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'image' => $employee->image,
                    'name' => $employee->name,
                    'phone' => $employee->phone,
                    'division' => [
                        'id' => $employee->division->id,
                        'name' => $employee->division->name,
                    ],
                    'position' => $employee->position,
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diambil',
                'data' => [
                    'employees' => $mappedEmployees,
                ],
                'pagination' => [
                    'current_page' => $employees->currentPage(),
                    'per_page' => $employees->perPage(),
                    'total' => $employees->total(),
                    'last_page' => $employees->lastPage(),
                ],
            ], 200);
        }
    }

    public function store(StoreEmployeeRequest $request)
    {
        $validated = $request->validated();

        $imagePath = $request->file('image')?->store('employees', 'public');

        Employee::create([
            'image' => $imagePath,
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'division_id' => $validated['division_id'],
            'position' => $validated['position'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data karyawan berhasil ditambahkan',
        ], 200);
    }

    public function update(UpdateEmployeeRequest $request, $id)
    {
        $validated = $request->validated();

        $employee = Employee::findOrFail($id);

        // Jika ada gambar baru, hapus yang lama & simpan yang baru
        if ($request->hasFile('image')) {
            if ($employee->image) {
                Storage::disk('public')->delete($employee->image);
            }
            $employee->image = $request->file('image')->store('employees', 'public');
        }

        // Update data lain
        $employee->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'division_id' => $validated['division_id'],
            'position' => $validated['position'],
            'image' => $employee->image,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data karyawan berhasil diperbarui',
        ]);
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        if ($employee->image) {
            Storage::disk('public')->delete($employee->image);
        }
        $employee->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data karyawan berhasil dihapus',
        ]);
    }
}
