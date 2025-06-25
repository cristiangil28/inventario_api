<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Services\ProductServiceInterface;
use App\Services\ProductService;
use App\Services\CategoryServiceInterface;
use App\Services\CategoryService;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function register(): void
    {
        
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);

        
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);

        $this->app->bind(ProductServiceInterface::class, ProductService::class);

        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
