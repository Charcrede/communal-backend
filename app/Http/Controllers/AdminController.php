<?php
namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index(): JsonResponse
    {
        $admins = Admin::select('id', 'name', 'email', 'role', 'created_at', 'updated_at')->get();
        return response()->json($admins);
    }

    public function show(string $id): JsonResponse
    {
        $admin = Admin::select('id', 'name', 'email', 'role', 'created_at', 'updated_at')
            ->findOrFail($id);
        return response()->json($admin);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8',
            'role' => 'sometimes|in:admin,super_admin',
        ]);

        $admin = Admin::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'admin',
        ]);

        return response()->json($admin->only(['id', 'name', 'email', 'role', 'created_at', 'updated_at']), 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $admin = Admin::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:admins,email,' . $id,
            'password' => 'sometimes|required|string|min:8',
            'role' => 'sometimes|in:admin,super_admin',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $admin->update($validated);
        return response()->json($admin->only(['id', 'name', 'email', 'role', 'created_at', 'updated_at']));
    }

    public function destroy(string $id): JsonResponse
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();
        return response()->json(null, 204);
    }
}