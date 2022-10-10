<?php

namespace App\Http\Controllers;

interface AlertInterface
{
    public function getAll();

    public function setData($request);

    public function productNameJoinToAlertById($id);

    public function destroy($id);

    public function update($id, $request);
}
