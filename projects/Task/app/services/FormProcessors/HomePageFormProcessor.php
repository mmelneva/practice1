<?php namespace App\Services\FormProcessors;

use App\Models\HomePage;
use App\Services\FormProcessors\HomePagesSubProcessor\HomePageSubProcessorInterface;
use App\Services\Validation\HomePage\HomePageLaravelValidator;
use App\Services\Validation\ValidableInterface;

/**
 * Class HomePageFormProcessor
 * @package  App\Services\FormProcessors
 */
class HomePageFormProcessor
{
    /**
     * @var HomePageLaravelValidator
     */
    protected $validator;

    /**
     * @var HomePageSubProcessorInterface[]
     */
    protected $subProcessors;

    /**
     * @param HomePageLaravelValidator $validator
     */
    public function __construct(HomePageLaravelValidator $validator)
    {
        $this->validator = $validator;
        $this->subProcessors = [];
    }

    /**
     * Save element
     *
     * @param HomePage $homePage
     * @param array $data
     * @return HomePage|null
     */
    public function save(HomePage $homePage, array $data = [])
    {
        $this->validator->setCurrentId($homePage->id);
        if ($this->validator->with($data)->passes()) {
            $homePage->fill($data);
            $homePage->save();

            $this->afterProcess($homePage, $data);

            return $homePage;
        } else {
            return null;
        }
    }

    /**
     * Get errors.
     *
     * @return array
     */
    public function errors()
    {
        return $this->validator->errors();
    }

    /**
     * Prepare input data before validation and creating/updating.
     *
     * @param array $data
     * @return array
     */
    protected function prepareInputData(array $data)
    {
        foreach ($this->subProcessors as $subProcessor) {
            $data = $subProcessor->prepareInputData($data);
        }

        return $data;
    }

    /**
     * Add sub processor.
     *
     * @param HomePageSubProcessorInterface $subProcessor
     */
    public function addSubProcessor(HomePageSubProcessorInterface $subProcessor)
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
