<?php

namespace App\Service;

use App\Exceptions\OrderValidatorException;
use App\Http\Controllers\OrderInterface;
use App\Models\Order;

class OrderRepositoryService implements OrderInterface
{
    public function getAll()
    {
        return Order::get();
    }

    public function setData($request)
    {
        return Order::create([
            'nameBuyer' => $request->nameBuyer,
            'dateOrder'=> $request->dateOrder,
            'dateDeliver' => $request->dateDeliver,
        ]);
    }

    /**
     * @throws OrderValidatorException
     */
    public function show($id)
    {
        $this->checkOrderIdExist($id);

        return Order::where('id', $id)->get();
    }

    public function destroy($id)
    {
        $this->checkOrderIdExist($id);

        $order = Order::find($id);
        $order->products()->detach();
        $order->delete();

        return true;
    }

    /**
     * @throws OrderValidatorException
     */
    public function update($id, $request)
    {
        $this->checkOrderIdExist($id);

        $order = Order::find($id);
        $order->fill($request->all())->save();

        return $order;
    }

    public function showOrderByIdRelationToProduct($id)
    {
        return Order::find($id)->products()->get();
    }


    /**
     * @throws OrderValidatorException
     */
    public function checkOrderIdExist($id): bool
    {
        $product = Order::find($id);
        if ($product === null) {
            throw new OrderValidatorException();
        }

        return true;
    }
}
