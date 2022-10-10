<?php

namespace App\Service;

use App\Http\Controllers\AlertInterface;
use App\Models\Alert;
use Illuminate\Http\Request;

class AlertRepositoryService implements AlertInterface
{
    public function getAll()
    {
        return Alert::get();
    }

    public function setData($request)
    {
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

    public function destroy($id)
    {
        $alert = Alert::find($id);
        $alert->delete();
        return true;
    }

    public function update($id, $request)
    {
        $alert = Alert::find($id);
        $alert->fill($request->input())->save();
        return $alert;
    }
}
