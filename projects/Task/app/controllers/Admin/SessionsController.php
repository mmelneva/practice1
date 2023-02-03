<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * Class SessionsController
 * @package App\Controllers\Admin
 */
class SessionsController extends BaseController
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Response
     */
    public function create()
    {
        return \View::make('admin.session.login', ['title' => 'Авторизация']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Response
     */
    public function store()
    {
        $credentials = \Input::only('username', 'password');
        $credentials['active'] = true;
        if (\Auth::attempt($credentials, \Input::has('remember'))) {
            return \Redirect::intended(action('App\Controllers\Admin\AclLoginRouterController@getRoute'));
        }

        return \View::make('admin.session.login', ['title' => 'Авторизация', 'incorrect' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Response
     */
    public function destroy()
    {
        \Auth::logout();

        return \Redirect::route('cc.login');
    }
}
