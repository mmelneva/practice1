<?php namespace App\Services\FormProcessors;

/**
 * Class AdminUserFormProcessor
 * @package App\Services\FormProcessors
 */
class AdminUserFormProcessor extends CreateUpdateFormProcessor
{
    protected function prepareInputData(array $data)
    {
        if (isset($data['password']) && $data['password'] === '') {
            unset($data['password']);
        }

        return $data;
    }
}
