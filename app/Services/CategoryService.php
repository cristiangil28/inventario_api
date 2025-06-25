<?php

namespace App\Services;

use App\Repositories\CategoryRepositoryInterface;

class CategoryService implements CategoryServiceInterface
{
    protected $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }  

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function getAll()
    {
        return $this->repository->all();
    }

    public function find(int $id)
    {
        return $this->repository->find($id);
    }
}
