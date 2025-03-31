<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return $products;
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());

        return $product;
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->updateOrFail($request->validated());

        return $product;
    }

    public function destroy(int $id)
    {
        $product = Product::withTrashed()->findOrFail($id);

        $product->delete();

        return $product;
    }
}
