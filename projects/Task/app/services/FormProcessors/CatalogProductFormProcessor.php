<?php namespace App\Services\FormProcessors;

use App\Services\FormProcessors\CatalogProductsSubProcessor\CatalogProductSubProcessorInterface;
use App\Services\Repositories\CatalogProduct\CatalogProductRepositoryInterface;
use App\Services\Validation\CatalogProduct\CatalogProductLaravelValidator;
use App\Services\Validation\ValidableInterface;

class CatalogProductFormProcessor
{
    /**
     * @var ValidableInterface
     */
    protected $validator;
    /**
     * @var CatalogProductRepositoryInterface
     */
    protected $repository;

    /**
     * @var CatalogProductSubProcessorInterface[]
     */
    protected $subProcessors;

    public function __construct(
        CatalogProductLaravelValidator $validator,
        CatalogProductRepositoryInterface $repository
    ) {
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

    protected function prepareInputData(array $data)
    {
        $nullIfEmptyFields = ['price'];

        foreach ($nullIfEmptyFields as $field) {
            if (array_get($data, $field) === '') {
                $data[$field] = null;
            }
        }

        $nullPossible = [];

        foreach ($nullPossible as $field) {
            if (array_get($data, $field) == 0) {
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
     * @param CatalogProductSubProcessorInterface $subProcessor
     */
    public function addSubProcessor(CatalogProductSubProcessorInterface $subProcessor)
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
