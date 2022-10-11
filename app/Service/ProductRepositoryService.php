<?php

namespace App\Service;

use App\Exceptions\ProductValidatorException;
use App\Http\Controllers\ProductInterface;
use App\Models\Product;

class ProductRepositoryService implements ProductInterface
{
    public function getAll()
    {
        return Product::get();
    }

    public function setData($request)
    {
        return Product::create([
            'name' => $request->name,
            'category'=> $request->category,
            'company' => $request->company,
            'amount' => $request->amount,
            'price' => $request->price,
        ]);
    }

    /**
     * @throws ProductValidatorException
     */
    public function show($id)
    {
        $this->checkProductIdExist($id);

        return Product::where('id', $id)->get();
    }

    public function destroy($id)
    {
        $this->checkProductIdExist($id);

        $product = Product::find($id);
        $product->orders()->detach();
        $product->delete();

        return true;
    }

    /**
     * @throws ProductValidatorException
     */
    public function update($id, $request)
    {
        $this->checkProductIdExist($id);

        $product = Product::find($id);
        $product->fill($request->all())->save();

        return $product;
    }

    public function showProductByIdRelationToOrder($id)
    {
        return Product::find($id)
            ->orders()
            ->get();
    }

    /**
     * @throws ProductValidatorException
     */
    public function checkProductIdExist($product_id): bool
    {
        $product = Product::find($product_id);
        if ($product === null) {
            throw new ProductValidatorException();
        }

        return true;
    }
}
