<?php

namespace App\Repositories;

interface ProductRepositoryInterface
{
    public function allWithCategory();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
