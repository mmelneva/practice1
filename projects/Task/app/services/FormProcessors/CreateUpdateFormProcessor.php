<?php namespace App\Services\FormProcessors;

use App\Services\Repositories\CreateUpdateRepositoryInterface;
use App\Services\Validation\ValidableInterface;

/**
 * Class CreateUpdateFormProcessor
 * @package App\Services\FormProcessors
 */
class CreateUpdateFormProcessor implements CrudFormProcessorInterface
{
    /**
     * @var ValidableInterface
     */
    protected $validator;
    /**
     * @var CreateUpdateRepositoryInterface
     */
    protected $repository;

    /**
     * @param ValidableInterface $validator
     * @param CreateUpdateRepositoryInterface $repository
     */
    public function __construct(ValidableInterface $validator, CreateUpdateRepositoryInterface $repository)
    {
        $this->validator = $validator;
        $this->repository = $repository;
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
            return $this->repository->create($data);
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
        if (!empty($data['name'])) {
            $data['name'] = trim($data['name']);
        }

        return $data;
    }
}
