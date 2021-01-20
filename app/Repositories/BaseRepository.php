<?php
namespace App\Repositories;

use Hyperzod\HyperzodServiceFunctions\Traits\ServiceResponseTrait;
use Hyperzod\HyperzodServiceFunctions\Traits\SettingsServiceTrait;

abstract class BaseRepository
{
    use ServiceResponseTrait, SettingsServiceTrait;
}
