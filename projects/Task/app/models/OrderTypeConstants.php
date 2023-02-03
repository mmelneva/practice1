<?php namespace App\Models;

use App\Models\Features\ConstantsGetter;

/**
 * Class OrderTypeConstants
 * @package  App\Models
 */
class OrderTypeConstants
{
    use ConstantsGetter;

    const FROM_SITE = 'from_site';
    const FAST = 'fast';
    const INCOMPLETE = 'incomplete';
}
