<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Hyperzod\HyperzodServiceFunctions\Traits\ApiResponseTrait;
use Hyperzod\HyperzodServiceFunctions\Traits\AuthTrait;

class Controller extends BaseController
{
    use ApiResponseTrait, AuthTrait;
}
