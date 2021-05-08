<?php
namespace App\Repositories;

use Hyperzod\HyperzodServiceFunctions\Traits\ApiResponseTrait;
use Hyperzod\HyperzodServiceFunctions\Traits\SettingsServiceTrait;

abstract class BaseRepository
{
    use ApiResponseTrait, SettingsServiceTrait;
}
