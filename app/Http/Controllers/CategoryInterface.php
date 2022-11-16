<?php

namespace App\Http\Controllers;

interface CategoryInterface
{
    public function getAll();

    public function setData($request);

    public function show($id);

    public function destroy($id);

    public function update($id, $request);

    public function findMany($ids);

    public function selectCategoriesIds($categories);

}
