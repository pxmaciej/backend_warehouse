<?php

namespace App\Service;

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

    public function show($id)
    {
        return Product::where('id', $id)->get();
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        $product->orders()->detach();
        $product->delete();

        return true;
    }

    public function update($id, $request)
    {
        $product = Product::find($id);
        $product->fill($request->input())->save();
        return $product;
    }

    public function showProductByIdRelationToOrder($id)
    {
        return Product::find($id)
            ->orders()
            ->get();
    }
}
