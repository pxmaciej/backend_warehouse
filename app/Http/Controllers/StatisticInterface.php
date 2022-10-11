<?php

namespace App\Http\Controllers;

interface StatisticInterface
{
    public function getAll();

    public function setData($request);

    public function show($id);

    public function destroy($id);

    public function update($id, $request);

    public function checkProductIdExist($product_id);
}
