<?php namespace App\Services\Validation\AdminRole;

use App\Services\Validation\AbstractLaravelValidator;
use Diol\Laracl\Acl;
use Illuminate\Validation\Factory as ValidatorFactory;

/**
 * Class AdminRoleLaravelValidator
 * @package App\Services\Validation\AdminRole
 */
class AdminRoleLaravelValidator extends AbstractLaravelValidator
{
    private $acl;

    public function __construct(ValidatorFactory $validatorFactory, Acl $acl)
    {
        parent::__construct($validatorFactory);
        $this->acl = $acl;
    }

    protected function getRules()
    {
        return [
            'name' => ['required', "unique:admin_roles,name,{$this->currentId}"],
            'rules' => 'subset:' . implode(',', array_keys($this->acl->getResourceRuleList())),
        ];
    }
}
