<?php

namespace App\Http\Controllers;

interface OrderInterface
{
    public function getAll();

    public function setData($request);

    public function show($id);

    public function destroy($id);

    public function update($id, $request);

    public function showOrderByIdRelationToProduct($id);
}
