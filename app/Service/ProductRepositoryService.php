<?php

namespace App\Service;

use App\Exceptions\NotFoundException;
use App\Http\Controllers\ProductInterface;
use App\Models\Product;

class ProductRepositoryService implements ProductInterface
{
    public function getAll()
    {
        $products = Product::with('categories')->get();

        return $products;
    }

    public function setData($request)
    {
        return Product::create([
            'name' => $request->name,
            'company' => $request->company,
            'amount' => $request->amount,
            'price' => $request->price,
        ]);
    }

    /**
     * @throws NotFoundException
     */
    public function show($id)
    {
        $this->checkProductIdExist($id);

        return Product::with('categories')->find($id);
    }

    /**
     * @throws NotFoundException
     */
    public function destroy($id)
    {
        $this->checkProductIdExist($id);

        $product = Product::find($id);
        $product->orders()->detach();
        $product->categories()->detach();
        $product->delete();

        return true;
    }

    /**
     * @throws NotFoundException
     */
    public function update($id, $request)
    {
        $this->checkProductIdExist($id);

        $product = Product::find($id);

        $product->fill($request->only(['name','company', 'amount', 'price']))->save();

        return $product;
    }

    /**
     * @throws NotFoundException
     */
    public function showProductByIdRelationToOrder($id)
    {
        $this->checkProductIdExist($id);

        return Product::find($id)
            ->orders()
            ->get();
    }

    /**
     * @throws NotFoundException
     */
    public function checkProductIdExist($product_id): bool
    {
        $product = Product::find($product_id);
        if ($product === null) {
            throw new NotFoundException();
        }

        return true;
    }
}
