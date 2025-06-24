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

    public function destroy($id)
    {
        $category = Category::find($id);
        if (! $category) {
            return response()->json(['message' => 'Categoría no encontrada'], 404);
        }

        $category->delete();
        return response()->json(['message' => 'Categoría eliminada']);
    }
}
