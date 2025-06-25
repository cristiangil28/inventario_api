<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\CategoryServiceInterface;

class CategoryController extends Controller
{
    protected $service;

    public function __construct(CategoryServiceInterface $service)
    {
        $this->service = $service;
    }
    
    public function index()
    {
        return $this->service->getAll();
    }

    public function show($id)
    {
        $category = $this->service->find($id);
        return $category
            ? response()->json($category)
            : response()->json(['message' => 'Categoría no encontrada'], 404);
    }

    public function store(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Acceso denegado. Solo los administradores pueden actualizar categorías.'
            ], 403);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = $this->service->create($validator->validated());

        return response()->json($category, 201);
    }


    public function update(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Acceso denegado. Solo los administradores pueden actualizar categorías.'
            ], 403);
        }

        $category = $this->service->find($id);
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

        $category= $this->service->update($category->id,$validator->validated());
        return response()->json($category);
    }

    public function destroy(Request $request,$id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Acceso denegado. Solo los administradores pueden eliminar categorías.'
            ], 403);
        }

        $category = $this->service->find($id);
        if (! $category) {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }

        $this->service->delete($category->id);
        return response()->json(['message' => 'Categoría eliminada']);
    }
}
