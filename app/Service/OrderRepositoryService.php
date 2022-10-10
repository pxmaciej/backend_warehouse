<?php

namespace App\Service;

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

    public function show($id)
    {
        return Order::where('id', $id)->get();
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        $order->products()->detach();
        $order->delete();
        return true;
    }

    public function update($id, $request)
    {
        $order = Order::find($id);
        $order->fill($request->input())->save();
        return $order;
    }

    public function showOrderByIdRelationToProduct($id)
    {
        return Order::find($id)->products()->get();
    }
}
