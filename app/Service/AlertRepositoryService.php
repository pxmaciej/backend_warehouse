<?php

namespace App\Service;

use App\Exceptions\AlertValidatorException;
use App\Exceptions\NotFoundException;
use App\Http\Controllers\AlertInterface;
use App\Models\Alert;
use App\Models\Product;

class AlertRepositoryService implements AlertInterface
{
    public function getAll()
    {
        return Alert::get();
    }

    /**
     * @throws NotFoundException
     */
    public function setData($request)
    {
        $this->checkProductIdExist($request->product_id);

        return Alert::create(
            [
                'product_id' => $request->product_id,
                'name'=> $request->name,
            ]
        );
    }

    /**
     * @throws NotFoundException
     */
    public function productNameJoinToAlertById($id)
    {
        $this->checkAlertIdExist($id);

        return Alert::join('products', 'products.id', '=', 'alerts.product_id')
            ->select('products.name as product_name','alerts.*')
            ->where('alerts.id', $id)
            ->get();
    }

    /**
     * @throws NotFoundException
     */
    public function destroy($id): bool
    {
        $this->checkAlertIdExist($id);

        $alert = Alert::find($id);
        $alert->delete();

        return true;
    }

    /**
     * @throws AlertValidatorException
     * @throws NotFoundException
     */
    public function update($id, $request)
    {
        $this->checkAlertIdExist($id);
        $this->checkProductIdExist($request->product_id);

        $alert = Alert::find($id);
        $alert->fill($request->input())->save();

        return $alert;
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
    public function checkAlertIdExist($id): bool
    {
        $product = Alert::find($id);
        if ($product === null) {
            throw new NotFoundException();
        }

        return true;
    }
}
