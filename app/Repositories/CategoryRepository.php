<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function all()
    {
        return Category::all();
    }

    public function find($id)
    {
        return Category::find($id);
    }

    public function create(array $data)
    {
        return Category::create($data);
    }

    public function update($id, array $data)
    {
        $category = Category::find($id);
        return $category ? tap($category)->update($data) : null;
    }

    public function delete($id)
    {
        $category = Category::find($id);
        return $category ? $category->delete() : false;
    }
}
