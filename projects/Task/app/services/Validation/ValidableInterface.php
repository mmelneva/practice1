<?php namespace App\Services\Validation;

/**
 * Interface ValidableInterface
 * @package App\Services\Validation
 */
interface ValidableInterface
{
    /**
     * Add data to validation against.
     *
     * @param array
     * @return self
     */
    public function with(array $input);

    /**
     * Test if validation passes.
     *
     * @return boolean
     */
    public function passes();

    /**
     * Retrieve validation errors.
     *
     * @return array
     */
    public function errors();

    /**
     * Set id of current object data if needed (for unique validation for example).
     *
     * @param $id
     * @return self
     */
    public function setCurrentId($id);
}
