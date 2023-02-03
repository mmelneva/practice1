<?php namespace App\Models;

use App\Models\Features\ConstantsGetter;

/**
 * Class OrderStatusConstants
 * @package  App\Models
 */
class OrderStatusConstants
{
    use ConstantsGetter;

    const NOVEL = 'novel';
    const PROCESSED = 'processed';
    const CANCELLED = 'cancelled';
    const EXECUTED = 'executed';
    const RETURNS = 'returns';
    const REFUSAL = 'refusal';
}
