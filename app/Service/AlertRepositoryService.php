<?php

namespace App\Service;

use App\Exceptions\AlertValidatorException;
use App\Http\Controllers\AlertInterface;
use App\Models\Alert;
use App\Models\Product;
use Illuminate\Http\Request;

class AlertRepositoryService implements AlertInterface
{
    public function getAll()
    {
        return Alert::get();
    }

    /**
     * @throws AlertValidatorException
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

    public function productNameJoinToAlertById($id)
    {
        return Alert::join('products', 'products.id', '=', 'alerts.product_id')
            ->select('products.name as product_name','alerts.*')
            ->where('alerts.id', $id)
            ->get();
    }

    /**
     * @throws AlertValidatorException
     */
    public function destroy($id)
    {
        $this->checkAlertIdExist($id);

        $alert = Alert::find($id);
        $alert->delete();

        return true;
    }

    /**
     * @throws AlertValidatorException
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
     * @throws AlertValidatorException
     */
    public function checkProductIdExist($id): bool
    {
        $product = Product::find($id);
        if ($product === null) {
            throw new AlertValidatorException();
        }

        return true;
    }

    /**
     * @throws AlertValidatorException
     */
    public function checkAlertIdExist($id): bool
    {
        $product = Alert::find($id);
        if ($product === null) {
            throw new AlertValidatorException();
        }

        return true;
    }

}
