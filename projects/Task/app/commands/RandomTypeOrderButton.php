<?php namespace App\Commands;

use Illuminate\Console\Command;

class RandomTypeOrderButton extends Command
{
    protected $name = 'app:set-random-type-order-button';
    protected $description = 'Set random type for order button';

    public function fire()
    {
        \App::make('App\Services\OrderButton\OrderButtonTypeGenerator')->setRandomParameter();
    }
}