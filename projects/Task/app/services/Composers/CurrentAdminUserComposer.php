<?php namespace App\Services\Composers;

/**
 * Class CurrentAdminUserComposer
 * @package App\SocioCompass\Composers
 */
class CurrentAdminUserComposer
{
    public function compose($view)
    {
        $view->with('currentAdminUser', \Auth::user());
    }
}
