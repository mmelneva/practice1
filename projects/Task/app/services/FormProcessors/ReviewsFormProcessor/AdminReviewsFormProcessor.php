<?php namespace App\Services\FormProcessors\ReviewsFormProcessor;

use App\Services\Repositories\Reviews\ReviewsRepositoryInterface;
use App\Services\FormProcessors\ReviewsSubProcessor\ReviewsSubProcessorInterface;
use App\Services\Validation\Reviews\AdminReviewsLaravelValidator;

/**
 * Class AdminReviewsFormProcessor
 * @package App\Services\FormProcessors\ReviewsFormProcessor
 */
class AdminReviewsFormProcessor extends ReviewsFormProcessor
{
    /**
     * @var ReviewsSubProcessorInterface[]
     */
    protected $subProcessors;

    public function __construct(
        AdminReviewsLaravelValidator $validator,
        ReviewsRepositoryInterface $repository
    ) {
        parent::__construct($validator, $repository);
        $this->subProcessors = [];
    }

    /**
     * Create an element.
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function create(array $data = [])
    {
        $data = $this->prepareInputData($data);
        if ($this->validator->with($data)->passes()) {
            $instance = $this->repository->create($data);
            $this->afterProcess($instance, $data);

            return $instance;
        } else {
            return null;
        }
    }
    
    public function update($id, array $data = [])
    {
        $instance = parent::update($id, $data);

        $this->afterProcess($instance, $data);

        if (!is_null($instance) && !empty($data['optional']) && $data['optional'] == 'send_answer') {
            \ReviewsMailsHelper::sendClientReviewsAnswerEmail($instance);
        }

        return $instance;
    }

    protected function prepareInputData(array $data)
    {
        $data = parent::prepareInputData($data);

        foreach ($this->subProcessors as $subProcessor) {
            $data = $subProcessor->prepareInputData($data);
        }

        return $data;
    }

    /**
     * Add sub processor.
     *
     * @param ReviewsSubProcessorInterface $subProcessor
     */
    public function addSubProcessor(ReviewsSubProcessorInterface $subProcessor)
    {
        $this->subProcessors[] = $subProcessor;
    }

    /**
     * Run sub processors.
     *
     * @param $instance
     * @param $data
     */
    protected function afterProcess($instance, $data)
    {
        if (is_null($instance)) {
            return;
        }

        foreach ($this->subProcessors as $subProcessor) {
            $subProcessor->process($instance, $data);
        }
    }

}
