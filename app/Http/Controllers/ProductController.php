<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::with('category')->get());
    }

    public function show($id)
    {
        $product = Product::with('category')->find($id);
        if (! $product) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }
        return response()->json($product);
    }

    public function store(Request $request)
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

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = Product::create($validator->validated());
        return response()->json($product, 201);
    }

    public function update(Request $request, $id)
    {
         if (! $request->user()) {
            return response()->json([
                'message' => 'Token no válido o sesión expirada. Por favor, inicie sesión.'
            ], 401);
        }

    
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Acceso denegado. Solo los administradores pueden actualizar categorías.'
            ], 403);
        }

        $product = Product::find($id);
        if (! $product) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'category_id' => 'sometimes|required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product->update($validator->validated());
        return response()->json($product);
    }

    public function destroy(Request $request, $id)
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

        $product = Product::find($id);
        if (! $product) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $product->delete();
        return response()->json(['message' => 'Producto eliminado']);
    }
}
