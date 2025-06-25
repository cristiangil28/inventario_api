<?php

namespace App\Services;

interface CategoryServiceInterface
{
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id): bool;
    public function getAll();
    public function find(int $id);
}
