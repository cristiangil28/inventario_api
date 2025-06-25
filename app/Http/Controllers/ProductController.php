<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repositories\ProductRepositoryInterface;

class ProductController extends Controller
{
    protected $products;

    public function __construct(ProductRepositoryInterface $repo)
    {
        // Aquí podrías inyectar un repositorio si lo necesitas
         $this->products = $repo;
    }
    
    public function index()
    {
        return $this->products->allWithCategory();
    }

    public function show($id)
    {
        $product = $this->products->find($id);
        return $product
            ? response()->json($product)
            : response()->json(['message' => 'Producto no encontrado'], 404);
    }

    public function store(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Acceso denegado. Solo los administradores pueden crear Productos.'
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

        $product = $this->products->create($validator->validated());
        return response()->json($product, 201);
    }

    public function update(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Acceso denegado. Solo los administradores pueden actualizar Productos.'
            ], 403);
        }

        $product = $this->products->find($id);
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

        $product = $this->products->update($product->id ,$validator->validated());
        return response()->json($product);
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Acceso denegado. Solo los administradores pueden eliminar Productos.'
            ], 403);
        }

        $product = $this->products->find($id);
        if (! $product) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $this->products->delete($product->id);
        return response()->json(['message' => 'Producto eliminado']);
    }
}
