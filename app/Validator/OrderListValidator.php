<?php

namespace App\Validator;

use App\Exceptions\OrderListValidatorException;
use Illuminate\Support\Facades\Validator;

class OrderListValidator
{

    private $validator;

    public function __construct(Validator $validator) {
        $this->validator = $validator;
    }

    /**
     * @param $request
     * @return bool
     * @throws OrderListValidatorException
     */
    public function productIdOrderIdAmountPriceRequired($request): bool
    {
        $validator = $this->validator::make($request->all(),
            [
                'product_id' => 'required',
                'order_id' => 'required',
                'amount' => 'required',
                'netto'=> 'required',
                'vat'=> 'required',
                'brutto'=> 'required'
            ]
        );

        if ($validator->fails()) {
            throw new OrderListValidatorException();
        }

        return  true;
    }

    /**
     * @param $id
     * @return bool
     * @throws OrderListValidatorException
     */
    public function validateId($id): bool
    {
        if (!is_numeric($id) && is_null($id)) {
            throw new OrderListValidatorException();
        }

        return true;
    }
}
