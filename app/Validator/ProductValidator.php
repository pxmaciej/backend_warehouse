<?php

namespace App\Validator;

use App\Exceptions\ProductValidatorException;
use Illuminate\Support\Facades\Validator;

class ProductValidator
{
    /**
     * @var Validator
     */
    private $validator;

    public function __construct(Validator $validator) {
        $this->validator = $validator;
    }

    /**
     * @param $request
     * @return bool
     * @throws ProductValidatorException
     */
    public function nameCategoryCompanyAMountPriceRequired($request): bool
    {
        $validator = $this->validator::make($request->all(),
            [
                'name' => 'required|string',
                'category' => 'required|string',
                'company' => 'required|string',
                'amount' => 'required',
                'price'=> 'required',
            ]
        );

        if ($validator->fails()) {
            throw new ProductValidatorException();
        }

        return  true;
    }

    /**
     * @param $id
     * @return bool
     * @throws ProductValidatorException
     */
    public function validateId($id): bool
    {
        if (!is_numeric($id) && is_null($id)) {
            throw new ProductValidatorException();
        }

        return true;
    }
}
