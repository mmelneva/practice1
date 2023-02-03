<?php

class SettingsSeeder extends Seeder
{
    public function run()
    {
        /** @var \App\Services\Settings\SettingPublisher $publisher */
        $publisher = \App::make('App\Services\Settings\SettingPublisher');
        $publisher->publish();
    }
}
