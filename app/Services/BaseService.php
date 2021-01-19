<?php
namespace App\Services;

use Hyperzod\HyperzodServiceFunctions\Traits\ServiceResponseTrait;
use Hyperzod\HyperzodServiceFunctions\Traits\SettingsServiceTrait;

abstract class BaseService
{
    use ServiceResponseTrait, SettingsServiceTrait;
}
