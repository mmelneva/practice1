<?php namespace App\Models;

use App\Models\Features\ConstantsGetter;

/**
 * Class ProductBuiltInConstants
 * @package  App\Models
 */
class ProductBuiltInConstants
{
    use ConstantsGetter;

    const UNDEFINED = 0;
    const BUILT_IN = 1;
    const NOT_BUILT_IN = 2;
}
