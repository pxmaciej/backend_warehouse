<?php

namespace App\Validator;

use App\Exceptions\ProductValidatorException;
use App\Exceptions\StatisticValidatorException;
use Illuminate\Support\Facades\Validator;

class StatisticValidator
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
     * @throws StatisticValidatorException
     */
    public function nameCategoryCompanyAMountPriceRequired($request): bool
    {
        $validator = $this->validator::make($request->all(),
            [
                'product_id' => 'required',
                'name' => 'required',
                'amount' => 'required',
                'netto'=> 'required',
                'vat'=> 'required',
                'brutto'=> 'required'
            ]
        );

        if ($validator->fails()) {
            throw new StatisticValidatorException();
        }

        return  true;
    }

    /**
     * @param $id
     * @return bool
     * @throws StatisticValidatorException
     */
    public function validateId($id): bool
    {
        if (!is_numeric($id) && is_null($id)) {
            throw new StatisticValidatorException();
        }

        return true;
    }
}
