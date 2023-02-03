<?php namespace App\Services\FormProcessors\ReviewsFormProcessor;

use App\Services\FormProcessors\CreateUpdateFormProcessor;
use App\Services\Repositories\Reviews\ReviewsRepositoryInterface;
use App\Services\Validation\Reviews\ReviewsLaravelValidator;
use Carbon\Carbon;

/**
 * Class ReviewsFormProcessor
 * @package App\Services\FormProcessors\ReviewsFormProcessor
 */
class ReviewsFormProcessor extends CreateUpdateFormProcessor
{
    public function __construct(
        ReviewsLaravelValidator $validator,
        ReviewsRepositoryInterface $repository
    ) {
        parent::__construct($validator, $repository);
    }

    protected function prepareInputData(array $data)
    {
        $data = parent::prepareInputData($data);

        if (empty($data['date_at'])) {
            $data['date_at'] = Carbon::now()->format('Y-m-d H:i:s');
        } else {
            $data['date_at'] = date('Y-m-d H:i:s', strtotime($data['date_at']));
        }

        if (empty($data['product_id'])) {
            $data['product_id'] = null;
        }

        return $data;
    }

    public function update($id, array $data = [])
    {
        $instance = parent::update($id, $data);
        if (!is_null($instance) && !empty($data['optional']) && $data['optional'] == 'send_answer') {
            \ReviewsMailsHelper::sendClientReviewsAnswerEmail($instance);
        }

        return $instance;
    }
}
