<?php namespace App\Controllers\Admin;

use App\Controllers\Admin\Basic\ListCrudController;
use App\Services\FormProcessors\AdminRoleFormProcessor;
use App\Services\Repositories\AdminRole\AdminRoleRepositoryInterface;
use Diol\Laracl\Acl;

class AdminRolesController extends ListCrudController
{
    /**
     * @var Acl
     */
    private $acl;

    public function __construct(
        AdminRoleRepositoryInterface $repository,
        AdminRoleFormProcessor $formProcessor
    ) {
        parent::__construct($repository, $formProcessor);
        $this->acl = \App::make('admin_acl');
    }

    protected function getTexts()
    {
        return [
            'list_title' => 'Роли администратора',
            'add_new' => 'Добавить роль',
            'edit_title' => 'Редактирование роли администратора "{name}"',
            'create_title' => 'Создание новой роли администора',
            'delete_confirm' => 'Вы уверены, что хотите удалить данную роль?',
        ];
    }

    protected function getMessages()
    {
        return [
            'created' => 'Новая роль "{name}" успешно создана',
            'updated' => 'Роль "{name}" обновлена',
            'deleted' => 'Роль "{name}" удалена',
        ];
    }

    public function getViewConfiguration()
    {
        $viewConfiguration = parent::getViewConfiguration();
        $viewConfiguration['form'] = [
            ['field' => 'name', 'template' => 'admin.resource_fields._text_field'],
            ['field' => 'rules', 'template' => 'admin.admin_roles._rules_field'],
            ['template' => 'admin.resource_fields._timestamps'],
        ];

        return $viewConfiguration;
    }


    public function getCreate()
    {
        return parent::getCreate()->with('acl_rules', $this->getAclRuleVariants());
    }

    public function getEdit($id = null)
    {
        return parent::getEdit($id)->with('acl_rules', $this->getAclRuleVariants());
    }

    /**
     * Get acl rule variants.
     *
     * @return array
     */
    private function getAclRuleVariants()
    {
        $aclRuleVariants = [];
        foreach ($this->acl->getResourceRuleList() as $ruleIdentifier => $rule) {
            $aclRuleVariants[$ruleIdentifier] = $rule->getName();
        }

        return $aclRuleVariants;
    }
}
