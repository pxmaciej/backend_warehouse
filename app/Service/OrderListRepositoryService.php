<?php

namespace App\Service;

use App\Exceptions\NotFoundException;
use App\Http\Controllers\OrderListInterface;
use App\Models\Order;
use App\Models\OrderList;
use App\Models\Product;

class OrderListRepositoryService implements OrderListInterface
{
    public function getAll()
    {
        return OrderList::get();
    }

    /**
     * @throws NotFoundException
     */
    public function setData($request)
    {
        $this->checkProductIdExist($request->product_id);
        $this->checkOrderIdExist($request->order_id);

        return OrderList::create([
            'product_id' => $request->product_id,
            'order_id'=> $request->order_id,
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
        $this->checkOrderListIdExist($id);

        return OrderList::join('products', 'products.id', '=', 'order_lists.product_id')
            ->join('orders', 'orders.id', '=', 'order_lists.order_id')
            ->select('order_lists.*', 'products.name', 'orders.*')
            ->where('order_lists.id', $id)
            ->get();
    }

    /**
     * @throws NotFoundException
     */
    public function destroy($id)
    {
        $this->checkOrderListIdExist($id);

        $order = OrderList::find($id);
        $order->delete();

        return true;
    }

    /**
     * @throws NotFoundException
     */
    public function update($id, $request)
    {
        $this->checkOrderListIdExist($id);
        $this->checkProductIdExist($request->product_id);
        $this->checkOrderIdExist($request->order_id);

        $order = OrderList::find($id);
        $order->fill($request->input())->save();

        return $order;
    }

    public function showProductByIdRelationToOrder($id)
    {
        return OrderList::join('products', 'products.id', '=', 'order_lists.product_id')
            ->join('orders', 'orders.id', '=', 'order_lists.order_id')
            ->select('order_lists.*', 'products.name', 'orders.client')
            ->where('order_lists.order_id', $id)
            ->get();
    }


    /**
     * @throws NotFoundException
     */
    public function checkOrderListIdExist($id): bool
    {
        $product = OrderList::find($id);
        if ($product === null) {
            throw new NotFoundException();
        }

        return true;
    }

    /**
     * @throws NotFoundException
     */
    public function checkProductIdExist($id): bool
    {
        $product = Product::find($id);
        if ($product === null) {
            throw new NotFoundException();
        }

        return true;
    }

    /**
     * @throws NotFoundException
     */
    public function checkOrderIdExist($id): bool
    {
        $product = Order::find($id);
        if ($product === null) {
            throw new NotFoundException();
        }

        return true;
    }
}
