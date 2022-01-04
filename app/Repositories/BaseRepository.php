<?php
namespace App\Repositories;

use Hyperzod\HyperzodServiceFunctions\Traits\ApiResponseTrait;
use Hyperzod\HyperzodServiceFunctions\Traits\SettingsServiceTrait;

abstract class BaseRepository
{
    use ApiResponseTrait, SettingsServiceTrait;

    public $errors = null;

    public function getErrors()
    {
        return $this->errors;
    }

    public function castNumerics($items)
    {
        return array_map(function ($item) {
            if (is_numeric($item)) {
                $item = intval($item);
            }
            return $item;
        }, $items);
    }
}
