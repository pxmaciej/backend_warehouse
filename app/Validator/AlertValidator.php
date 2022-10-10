<?php

namespace App\Validator;

use App\Exceptions\AlertValidatorException;
use Illuminate\Support\Facades\Validator;

class AlertValidator
{

    private $validator;

    public function __construct(Validator $validator) {
        $this->validator = $validator;
    }

    /**
     * @throws AlertValidatorException
     */
    public function productIdAndNameRequired($request): bool
    {
        $validator = $this->validator::make($request->all(),
            [
                'product_id' => 'required',
                'name' => 'required',
            ]
        );

        if ($validator->fails()) {
            throw new AlertValidatorException();
        }

        return  true;
    }

    /**
     * @throws AlertValidatorException
     */
    public function validateId($id): bool
    {
        if (!is_numeric($id) && is_null($id)) {
            throw new AlertValidatorException();
        }

        return true;
    }
}
