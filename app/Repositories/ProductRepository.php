<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function allWithCategory()
    {        
         return Product::with('category')->get();
    }

    public function find($id)
    {
        return Product::with('category')->find($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update($id, array $data)
    {
        $product = Product::find($id);
        return $product ? tap($product)->update($data) : null;
    }

    public function delete($id)
    {
        $product = Product::find($id);
        return $product ? $product->delete() : false;
    }
}
