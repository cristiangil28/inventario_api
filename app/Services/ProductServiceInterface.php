<?php

namespace App\Services;

interface ProductServiceInterface
{
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id): bool;
    public function getAllWithCategory();
    public function find(int $id);
}
