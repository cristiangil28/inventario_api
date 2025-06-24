<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::all());
    }

    public function show($id)
    {
        $category = Category::with('products')->find($id);
        if (! $category) {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }
        return response()->json($category);
    }

    public function store(Request $request)
    {
        return response()->json([
            'message' => 'Método no permitido. Use POST para crear una categoría.'
        ], 405);
        if (! $request->hasHeader('Authorization')) {
            return response()->json([
                'message' => 'No se proporcionó un token de autenticación.'
            ], 401);
        }

        if (! $request->user()) {
            return response()->json([
                'message' => 'Token no válido o sesión expirada. Por favor, inicie sesión.'
            ], 401);
        }

    
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Acceso denegado. Solo los administradores pueden crear categorías.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = Category::create($validator->validated());
        return response()->json($category, 201);
    }

    public function update(Request $request, $id)
    {
        if (! $request->hasHeader('Authorization')) {
            return response()->json([
                'message' => 'No se proporcionó un token de autenticación.'
            ], 401);
        }

        if (! $request->user()) {
            return response()->json([
                'message' => 'Token no válido o sesión expirada. Por favor, inicie sesión.'
            ], 401);
        }

    
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Acceso denegado. Solo los administradores pueden crear categorías.'
            ], 403);
        }

        $category = Category::find($id);
        if (! $category) {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category->update($validator->validated());
        return response()->json($category);
    }

    public function destroy(Request $request,$id)
    {
        if (! $request->user()) {
            return response()->json([
                'message' => 'Token no válido o sesión expirada. Por favor, inicie sesión.'
            ], 401);
        }

    
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Acceso denegado. Solo los administradores pueden eliminar categorías.'
            ], 403);
        }

        $category = Category::find($id);
        if (! $category) {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }

        $category->delete();
        return response()->json(['message' => 'Categoría eliminada']);
    }
}
