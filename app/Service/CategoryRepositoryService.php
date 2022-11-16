<?php

namespace App\Service;

use App\Exceptions\CategoryValidatorException;
use App\Exceptions\NotFoundException;
use App\Http\Controllers\CategoryInterface;
use App\Models\Category;


class CategoryRepositoryService implements CategoryInterface
{

    public function getAll()
    {
        return Category::get();
    }

    public function setData($request)
    {
        return Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
    }

    /**
     * @throws NotFoundException
     */
    public function show($id)
    {
        $this->checkCategoryIdExist($id);

        return Category::find($id)->get();
    }

    public function findMany($ids)
    {
        return Category::findMany($ids);
    }

    public function selectCategoriesIds($categories)
    {
        if (is_array($categories)) {
            foreach ($categories as $category) {
                $ids[] = $category['id'];
            }

            return $ids;
        }

            return $categories;
    }

    /**
     * @throws NotFoundException
     */
    public function destroy($id)
    {
        $this->checkCategoryIdExist($id);

        $category = Category::find($id);
        $category->products()->detach();
        $category->delete();

        return true;
    }

    /**
     * @throws NotFoundException
     */
    public function update($id, $request)
    {
        $this->checkCategoryIdExist($id);

        $category = Category::find($id);
        $category->fill($request->all())->save();

        return $category;
    }

    /**
     * @throws NotFoundException
     */
    private function checkCategoryIdExist($id): void
    {
        $category = Category::find($id);
        if ($category === null) {
            throw new NotFoundException();
        }
    }
}
