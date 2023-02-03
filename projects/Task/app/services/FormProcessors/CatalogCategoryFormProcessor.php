<?php namespace App\Services\FormProcessors;

use App\Services\FormProcessors\CatalogCategoriesSubProcessor\CatalogCategoriesSubProcessorInterface;
use App\Services\FormProcessors\Features\AutoAlias;
use App\Services\Repositories\CatalogCategory\CatalogCategoryRepositoryInterface;
use App\Services\Validation\CatalogCategory\CatalogCategoryLaravelValidator;
use App\Services\Validation\ValidableInterface;

class CatalogCategoryFormProcessor
{
    use AutoAlias;

    /**
     * @var ValidableInterface
     */
    protected $validator;
    /**
     * @var CatalogCategoryRepositoryInterface
     */
    protected $repository;

    /**
     * @var CatalogCategoriesSubProcessorInterface[]
     */
    protected $subProcessors;

    /**
     * @param CatalogCategoryLaravelValidator $validator
     * @param CatalogCategoryRepositoryInterface $repository
     */
    public function __construct(CatalogCategoryLaravelValidator $validator, CatalogCategoryRepositoryInterface $repository)
    {
        $this->validator = $validator;
        $this->repository = $repository;
        $this->subProcessors = [];
    }

    protected function prepareInputData(array $data)
    {
        $data = $this->setAutoAlias($data);

        $nullIfEmptyFields = ['parent_id'];

        foreach ($nullIfEmptyFields as $field) {
            $value = array_get($data, $field);
            if (!is_null($value) && trim($value) === '') {
                $data[$field] = null;
            }
        }

        return $data;
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
     * Add sub processor.
     *
     * @param CatalogCategoriesSubProcessorInterface $subProcessor
     */
    public function addSubProcessor(CatalogCategoriesSubProcessorInterface $subProcessor)
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
