<?php namespace App\Controllers\Admin\Features;

use App\Services\Repositories\ToggleableRepositoryInterface;

trait ToggleFlags
{
    protected function prepareToggleFlag(
        ToggleableRepositoryInterface $repository,
        $id,
        $attribute,
        $action = 'putToggleAttribute'
    ) {
        $modelInstance = $repository->toggleAttribute($id, $attribute);
        if (!$modelInstance) {
            \App::abort(404, 'Resource not found');
        }

        if (\Request::ajax()) {
            $view = 'admin.shared._list_flag';
            $newFlagView = \View::make($view)
                ->with('element', $modelInstance)
                ->with('action', action(get_called_class() . '@' . $action, [$modelInstance->id, $attribute]))
                ->with('attribute', $attribute)
                ->render();

            return \Response::json(['new_icon' => $newFlagView]);
        } else {
            return \Redirect::action(get_called_class() . '@getIndex');
        }
    }

    protected function checkAndPrepareToggleFlag(
        ToggleableRepositoryInterface $repository,
        $id,
        $attribute,
        array $allowedAttributes = [],
        $action = 'putToggleAttribute'
    ) {
        if (in_array($attribute, $allowedAttributes)) {
            return $this->prepareToggleFlag($repository, $id, $attribute, $action);
        } else {
            \App::abort(404, 'Not allowed flag to toggle');

            return null;
        }
    }
}
