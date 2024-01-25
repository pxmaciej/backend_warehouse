<?php

namespace App\Service;

use App\Exceptions\NotFoundException;
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
            'client' => $request->client,
            'zipCode' => $request->zipCode,
            'city' => $request->city,
            'address' => $request->address,
            'type' => $request->type,
            'status' => $request->status,
            'dateOrder'=> $request->dateOrder,
            'dateDeliver' => $request->dateDeliver,
        ]);
    }

    /**
     * @throws NotFoundException
     */
    public function show($id)
    {
        $this->checkOrderIdExist($id);

        return Order::find($id)->get();
    }

    /**
     * @throws NotFoundException
     */
    public function destroy($id)
    {
        $this->checkOrderIdExist($id);

        $order = Order::find($id);
        $order->products()->detach();
        $order->delete();

        return true;
    }

    /**
     * @throws NotFoundException
     */
    public function update($id, $request)
    {
        $this->checkOrderIdExist($id);

        $order = Order::find($id);
        $order->fill($request->all())->save();

        return $order;
    }
    
    /**
     * @throws NotFoundException
     */
    public function showOrderByIdRelationToProduct($id)
    {
        $this->checkOrderIdExist($id);

        return Order::find($id)->products()->get();
    }


    /**
     * @throws NotFoundException
     */
    public function checkOrderIdExist($id): bool
    {
        $order = Order::find($id);

        if ($order === null) {
            throw new NotFoundException();
        }

        return true;
    }
}