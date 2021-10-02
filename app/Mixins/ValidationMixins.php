<?php

namespace App\Mixins;

use App\Exceptions\ExceptionWithArray;
use Illuminate\Validation\Validator;

trait ValidationMixins
{
    // Setting validators
    public function throwExceptionForErrors($validator)
    {
        if ($validator instanceof Validator) {

            if ($validator->fails()) throw new ExceptionWithArray("Validation failed", $validator->errors());
            if ($validator->passes()) return true;

        }

        if ($validator instanceof Validator == false) {
            throw new \Exception("\$validator should be an instance of Validator.");
        }

        throw new \Exception("Something went wrong.");

    }
}
