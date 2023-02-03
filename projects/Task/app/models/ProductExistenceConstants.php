<?php namespace App\Models;

use App\Models\Features\ConstantsGetter;

/**
 * Class OrderTypeConstants
 * @package  App\Models
 */
class ProductExistenceConstants
{
    use ConstantsGetter;

    const AVAILABLE = 1;
    const NOT_AVAILABLE = 2;
}
