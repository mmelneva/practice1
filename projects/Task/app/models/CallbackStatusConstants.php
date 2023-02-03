<?php namespace App\Models;

use App\Models\Features\ConstantsGetter;

/**
 * Class CallbackStatusConstants
 * @package  App\Models
 */
class CallbackStatusConstants
{
    use ConstantsGetter;

    const NOVEL = 'novel';
    const EXECUTED = 'executed';
}
