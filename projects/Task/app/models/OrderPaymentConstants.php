<?php namespace App\Models;

use App\Models\Features\ConstantsGetter;

/**
 * Class OrderPaymentConstants
 * @package  App\Models
 */
class OrderPaymentConstants
{
    use ConstantsGetter;

    const CASH = 'cash';
    const INVOICING_FOR_LEGAL_ENTITY = 'invoicing_for_legal_entity';
    const INVOICING_FOR_PHYSICAL_PERSON = 'invoicing_for_physical_person';
    const CASHLESS = 'cashless';
}
