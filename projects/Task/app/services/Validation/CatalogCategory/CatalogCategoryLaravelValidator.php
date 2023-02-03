<?php namespace App\Services\Validation\CatalogCategory;

use App\Services\Validation\AbstractLaravelValidator;

class CatalogCategoryLaravelValidator extends AbstractLaravelValidator
{
    protected function getRules()
    {
        return [
            'name' => 'required',
            'position' => 'integer',
            'alias' => ['required', "unique:catalog_categories,alias,{$this->currentId}"],
            'logo_file' => 'local_or_remote_image:jpeg,jpg,png,gif',
            'logo_active_file' => 'local_or_remote_image:jpeg,jpg,png,gif',
        ];
    }
}
