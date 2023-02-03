<?php namespace App\Services\FormProcessors;

use App\Services\Repositories\AdminRole\AdminRoleRepositoryInterface;
use App\Services\Validation\ValidableInterface;

class AdminRoleFormProcessor extends CreateUpdateFormProcessor
{
    public function __construct(ValidableInterface $validator, AdminRoleRepositoryInterface $repository)
    {
        parent::__construct($validator, $repository);
    }
}
