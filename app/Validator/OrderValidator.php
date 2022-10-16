<?php

namespace App\Validator;

use App\Exceptions\OrderValidatorException;
use Illuminate\Support\Facades\Validator;

class OrderValidator
{

    private $validator;

    public function __construct(Validator $validator) {
        $this->validator = $validator;
    }

    /**
     * @param $request
     * @return bool
     * @throws OrderValidatorException
     */
    public function nameBuyerAndDateOrderDeliverRequired($request): bool
    {
        $validator = $this->validator::make($request->all(),
            [
                'nameBuyer' => 'required',
                'address' => 'required',
                'status' => 'required',
                'dateOrder' => 'required',
                'dateDeliver' => 'required',
            ]
        );

        if ($validator->fails()) {
            throw new OrderValidatorException();
        }

        return  true;
    }

    /**
     * @param $id
     * @return bool
     * @throws OrderValidatorException
     */
    public function validateId($id): bool
    {
        if (!is_numeric($id) && is_null($id)) {
            throw new OrderValidatorException();
        }

        return true;
    }
}
