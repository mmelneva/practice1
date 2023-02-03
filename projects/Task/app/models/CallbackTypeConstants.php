<?php namespace App\Models;

use App\Models\Features\ConstantsGetter;

/**
 * Class CallbackTypeConstants
 * @package  App\Models
 */
class CallbackTypeConstants
{
    use ConstantsGetter;

    const CALLBACK = 'callback';
    const MEASUREMENT = 'measurement';
    const CONTACTS = 'contacts';
}
