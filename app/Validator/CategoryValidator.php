<?php

namespace App\Validator;

use App\Exceptions\CategoryValidatorException;
use Illuminate\Support\Facades\Validator;

class CategoryValidator
{
    private $validator;

    public function __construct(Validator $validator) {
        $this->validator = $validator;
    }

    /**
     * @throws CategoryValidatorException
     */
    public function categoryNameAndDescriptionRequired($request): bool
    {
        $validator = $this->validator::make($request->all(),
            [
                'name' => 'required',
                'description' => 'required',
            ]
        );

        if ($validator->fails()) {
            throw new CategoryValidatorException();
        }

        return  true;
    }

    /**
     * @throws CategoryValidatorException
     */
    public function validateId($id): bool
    {
        if (!is_numeric($id) && is_null($id)) {
            throw new CategoryValidatorException();
        }

        return true;
    }
}
