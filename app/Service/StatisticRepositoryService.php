<?php

namespace App\Service;


use App\Exceptions\NotFoundException;
use App\Http\Controllers\StatisticInterface;
use App\Models\Product;
use App\Models\Statistic;

class StatisticRepositoryService implements StatisticInterface
{
    public function getAll()
    {
        return Statistic::join('products', 'products.id', '=', 'statistics.product_id')
            ->select('products.name as product_name','statistics.*')
            ->get();
    }

    /**
     * @throws NotFoundException
     */
    public function setData($request)
    {
        $this->checkProductIdExist($request->product_id);

        return Statistic::create([
            'product_id' => $request->product_id,
            'name'=> $request->name,
            'amount' => $request->amount,
            'netto' => $request->netto,
            'vat' => $request->vat,
            'brutto' => $request->brutto,

        ]);
    }

    /**
     * @throws NotFoundException
     */
    public function show($id)
    {
        $this->checkStatisticIdExist($id);

        return Statistic::join('products', 'products.id', '=', 'statistics.product_id')
            ->select('products.name as product_name','statistics.*')
            ->where('statistics.id', $id)
            ->get();
    }

    /**
     * @throws NotFoundException
     */
    public function destroy($id)
    {
        $this->checkStatisticIdExist($id);

        $statistic = Statistic::find($id);
        $statistic->delete();

        return true;
    }

    /**
     * @throws NotFoundException
     */
    public function update($id, $request)
    {
        $this->checkStatisticIdExist($id);

        $statistic = Statistic::find($id);

        $this->checkProductIdExist($request->product_id);

        $statistic->fill($request->all())->save();

        return $statistic;
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

    /**
     * @throws NotFoundException
     */
    public function checkStatisticIdExist($id): bool
    {
        $product = Statistic::find($id);
        if ($product === null) {
            throw new NotFoundException();
        }

        return true;
    }
}
