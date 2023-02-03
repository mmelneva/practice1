<?php namespace App\Services\FormProcessors;

use App\Services\FormProcessors\AttributesSubProcessor\AttributeSubProcessorInterface;
use App\Services\Repositories\Attribute\AttributeRepositoryInterface;
use App\Services\Validation\Attribute\AttributeLaravelValidator;
use App\Services\Validation\ValidableInterface;

/**
 * Class AttributeFormProcessor
 * @package  App\Services\FormProcessors
 */
class AttributeFormProcessor implements CrudFormProcessorInterface
{
    /**
     * @var ValidableInterface
     */
    protected $validator;
    /**
     * @var AttributeRepositoryInterface
     */
    protected $repository;

    /**
     * @var AttributeSubProcessorInterface[]
     */
    protected $subProcessors;

    /**
     * @param AttributeLaravelValidator $validator
     * @param AttributeRepositoryInterface $repository
     */
    public function __construct(AttributeLaravelValidator $validator, AttributeRepositoryInterface $repository)
    {
        $this->validator = $validator;
        $this->repository = $repository;
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

    /**
     * Update an element.
     *
     * @param $id
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function update($id, array $data = [])
    {
        $data = $this->prepareInputData($data);
        $this->validator->setCurrentId($id);

        if ($this->validator->with($data)->passes()) {
            $instance = $this->repository->update($id, $data);
            $this->afterProcess($instance, $data);

            return $instance;
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
        $nullIfEmptyFields = [];

        foreach ($nullIfEmptyFields as $field) {
            $value = array_get($data, $field);
            if (!is_null($value) && trim($value) === '') {
                $data[$field] = null;
            }
        }

        foreach ($this->subProcessors as $subProcessor) {
            $data = $subProcessor->prepareInputData($data);
        }

        return $data;
    }

    /**
     * Add sub processor.
     *
     * @param AttributeSubProcessorInterface $subProcessor
     */
    public function addSubProcessor(AttributeSubProcessorInterface $subProcessor)
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
