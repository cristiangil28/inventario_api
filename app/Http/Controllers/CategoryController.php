<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repositories\CategoryRepositoryInterface;

class CategoryController extends Controller
{
    protected $categories;

    public function __construct(CategoryRepositoryInterface $categories)
    {
        $this->categories = $categories;
    }
    
    public function index()
    {
        return $this->categories->all();
    }

    public function show($id)
    {
        $category = $this->categories->find($id);
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

        $category = $this->categories->create($validator->validated());

        return response()->json($category, 201);
    }


    public function update(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Acceso denegado. Solo los administradores pueden actualizar categorías.'
            ], 403);
        }

        $category = $this->categories->find($id);
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

        $category= $this->categories->update($category->id,$validator->validated());
        return response()->json($category);
    }

    public function destroy(Request $request,$id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Acceso denegado. Solo los administradores pueden eliminar categorías.'
            ], 403);
        }

        $category = $this->categories->find($id);
        if (! $category) {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }

        $this->categories->delete($category->id);
        return response()->json(['message' => 'Categoría eliminada']);
    }
}
