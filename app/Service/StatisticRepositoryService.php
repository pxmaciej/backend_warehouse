<?php

namespace App\Service;

use App\Http\Controllers\ProductInterface;
use App\Models\Product;
use App\Models\Statistic;

class StatisticRepositoryService implements ProductInterface
{
    public function getAll()
    {
        return Statistic::get();
    }

    public function setData($request)
    {
        return Statistic::create([
            'name' => $request->name,
            'category'=> $request->category,
            'company' => $request->company,
            'amount' => $request->amount,
            'price' => $request->price,
        ]);
    }

    public function show($id)
    {
        return Statistic::where('id', $id)->get();
    }

    public function destroy($id)
    {
        $product = Statistic::find($id);
        $product->orders()->detach();
        $product->delete();

        return true;
    }

    public function update($id, $request)
    {
        $product = Statistic::find($id);
        $product->fill($request->input())->save();
        return $product;
    }

    public function showProductByIdRelationToOrder($id)
    {
        return Statistic::find($id)
            ->orders()
            ->get();
    }
}
