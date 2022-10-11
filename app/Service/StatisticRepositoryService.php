<?php

namespace App\Service;


use App\Exceptions\StatisticValidatorException;
use App\Http\Controllers\StatisticInterface;
use App\Models\Product;
use App\Models\Statistic;

class StatisticRepositoryService implements StatisticInterface
{
    public function getAll()
    {
        return Statistic::get();
    }

    /**
     * @throws StatisticValidatorException
     */
    public function setData($request)
    {
        $this->checkProductIdExist($request->product_id);

        return Statistic::create([
            'product_id' => $request->product_id,
            'name'=> $request->name,
            'amount' => $request->amount,
            'price' => $request->price,
        ]);
    }

    public function show($id)
    {
        $this->checkStatisticIdExist($id);

        return Statistic::join('products', 'products.id', '=', 'statistics.product_id')
            ->select('products.name as product_name','statistics.*')
            ->where('statistics.id', $id)
            ->get();
    }

    public function destroy($id): bool
    {
        $this->checkStatisticIdExist($id);

        $statistic = Statistic::find($id);
        $statistic->delete();

        return true;
    }

    /**
     * @throws StatisticValidatorException
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
     * @throws StatisticValidatorException
     */
    public function checkProductIdExist($product_id): bool
    {
        $product = Product::find($product_id);
        if ($product === null) {
            throw new StatisticValidatorException();
        }

        return true;
    }

    /**
     * @throws StatisticValidatorException
     */
    public function checkStatisticIdExist($id): bool
    {
        $product = Statistic::find($id);
        if ($product === null) {
            throw new StatisticValidatorException();
        }

        return true;
    }
}
