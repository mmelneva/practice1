<?php namespace App\Models;

use App\Models\Features\ConstantsGetter;

/**
 * Class OrderDeliveryConstants
 * @package  App\Models
 */
class OrderDeliveryConstants
{
    use ConstantsGetter;

    const OWN_EXPENSE = 'own_expense';
    const ADDRESS = 'address';
    const COURIER = 'courier';
}
