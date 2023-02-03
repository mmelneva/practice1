<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\FormProcessors\AdminUserFormProcessor;
use App\Services\Repositories\AdminRole\AdminRoleRepositoryInterface;
use App\Services\Repositories\AdminUser\AdminUserRepositoryInterface;

class AdminUsersController extends BaseController
{
    /**
     * @var AdminUserRepositoryInterface
     */
    private $adminUserRepository;
    /**
     * @var AdminUserFormProcessor
     */
    private $adminUserFormProcessor;
    /**
     * @var AdminRoleRepositoryInterface
     */
    private $adminRoleRepositoryInterface;

    public function __construct(
        AdminUserRepositoryInterface $adminUserRepository,
        AdminUserFormProcessor $adminUserFormProcessor,
        AdminRoleRepositoryInterface $adminRoleRepositoryInterface
    ) {
        $this->adminUserRepository = $adminUserRepository;
        $this->adminUserFormProcessor = $adminUserFormProcessor;
        $this->adminRoleRepositoryInterface = $adminRoleRepositoryInterface;
    }

    public function getIndex()
    {
        return \View::make('admin.admin_users.index')->with('user_list', $this->getUserList());
    }

    public function getCreate()
    {
        return \View::make('admin.admin_users.create')
            ->with('user', $this->adminUserRepository->newInstance())
            ->with('user_list', $this->getUserList())
            ->with('available_roles', $this->adminRoleRepositoryInterface->getVariants());
    }

    public function postStore()
    {
        $createdUser = $this->adminUserFormProcessor->create(\Input::except('redirect_to'));
        if (is_null($createdUser)) {
            return \Redirect::action(get_called_class() . '@getCreate')
                ->withErrors($this->adminUserFormProcessor->errors())->withInput();
        } else {
            if (\Input::get('redirect_to') == 'index') {
                $redirect = \Redirect::action(get_called_class() . '@getIndex');
            } else {
                $redirect = \Redirect::action(get_called_class() . '@getEdit', [$createdUser->id]);
            }

            return $redirect->with('alert_success', "Администратор {$createdUser->username} создан");
        }
    }

    public function getEdit($id = null)
    {
        $user = $this->adminUserRepository->find($id);
        if (is_null($user)) {
            \App::abort(404, 'Resource not found');
        }
        if ($user['super'] && !\Auth::user()->isSuper()) {
            \App::abort(403, 'Super user can be edited only by super user');
        }

        return \View::make('admin.admin_users.edit')
            ->with('user', $user)
            ->with('user_list', $this->getUserList())
            ->with('available_roles', $this->adminRoleRepositoryInterface->getVariants());
    }

    public function putUpdate($id = null)
    {
        if (\Auth::user()->id == $id) {
            $data = \Input::except('active', 'admin_role_id', 'redirect_to');
        } else {
            $data = \Input::except('redirect_to');
        }

        $user = $this->adminUserRepository->find($id);
        if (is_null($user)) {
            \App::abort(404, 'Resource not found');
        }
        if ($user['super'] && !\Auth::user()->isSuper()) {
            \App::abort(403);
        }

        $updatedUser = $this->adminUserFormProcessor->update($id, $data);
        if (is_null($updatedUser)) {
            return \Redirect::action(get_called_class() . '@getEdit', [$id])
                ->withErrors($this->adminUserFormProcessor->errors())->withInput();
        } else {
            if (\Input::get('redirect_to') == 'index') {
                $redirect = \Redirect::action(get_called_class() . '@getIndex');
            } else {
                $redirect = \Redirect::action(get_called_class() . '@getEdit', [$id]);
            }

            return $redirect->with('alert_success', "Администратор {$user->username} обновлён");
        }
    }

    public function deleteDestroy($id = null)
    {
        if (\Auth::user()->id == $id) {
            \App::abort(403);
        }

        $user = $this->adminUserRepository->find($id);
        if (is_null($user)) {
            \App::abort(404, 'Resource not found');
        }
        if ($user['super'] && !\Auth::user()->isSuper()) {
            \App::abort(403);
        }

        $this->adminUserRepository->delete($id);
        return \Redirect::action(get_called_class() . '@getIndex')
            ->with('alert_success', "Администратор {$user->username} удалён");
    }

    /**
     * Get user list.
     *
     * @return \App\Models\AdminUser[]
     */
    private function getUserList()
    {
        if (\Auth::user()->isSuper()) {
            $userList = $this->adminUserRepository->all();
        } else {
            $userList = $this->adminUserRepository->allWithoutSuper();
        }

        return $userList;
    }
}
