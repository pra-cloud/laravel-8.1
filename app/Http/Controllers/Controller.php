<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Hyperzod\HyperzodServiceFunctions\Traits\ApiResponseTrait;

class Controller extends BaseController
{
    use ApiResponseTrait;
}
