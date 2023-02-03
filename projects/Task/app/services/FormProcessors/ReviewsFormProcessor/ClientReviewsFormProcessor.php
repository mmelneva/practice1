<?php namespace App\Services\FormProcessors\ReviewsFormProcessor;

use App\Services\Repositories\Reviews\ReviewsRepositoryInterface;
use App\Services\Validation\Reviews\ClientReviewsLaravelValidator;


/**
 * Class ClientReviewsFormProcessor
 * @package App\Services\FormProcessors\ReviewsFormProcessor
 */
class ClientReviewsFormProcessor extends ReviewsFormProcessor
{
    public function __construct(
        ClientReviewsLaravelValidator $validator,
        ReviewsRepositoryInterface $repository
    ) {
        parent::__construct($validator, $repository);
    }

    public function create(array $data = [])
    {
        $instance = parent::create($data);
        if (!is_null($instance)) {
            \ReviewsMailsHelper::sendAdminNewReviewsEmail($instance);
        }

        return $instance;
    }
}
