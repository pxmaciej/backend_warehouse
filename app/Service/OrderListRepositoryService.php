<?php

namespace App\Service;

use App\Http\Controllers\OrderListInterface;
use App\Models\OrderList;

class OrderListRepositoryService implements OrderListInterface
{
    public function getAll()
    {
        return OrderList::get();
    }

    public function setData($request)
    {
        return OrderList::create([
            'product_id' => $request->product_id,
            'order_id'=> $request->order_id,
            'amount' => $request->amount,
            'price' => $request->price,
        ]);
    }

    public function show($id)
    {
        return OrderList::join('products', 'products.id', '=', 'order_lists.product_id')
            ->join('orders', 'orders.id', '=', 'order_lists.order_id')
            ->select('order_lists.*', 'products.name', 'orders.*')
            ->where('order_lists.id', $id)
            ->get();
    }

    public function destroy($id)
    {
        $order = OrderList::find($id);
        $order->delete();

        return true;
    }

    public function update($id, $request)
    {
        $order = OrderList::find($id);
        $order->fill($request->input())->save();

        return $order;
    }

    public function showProductByIdRelationToOrder($id)
    {
        return OrderList::join('products', 'products.id', '=', 'order_lists.product_id')
            ->join('orders', 'orders.id', '=', 'order_lists.order_id')
            ->select('order_lists.*', 'products.name', 'orders.NameBuyer')
            ->where('order_lists.order_id', $id)
            ->get();
    }
}
