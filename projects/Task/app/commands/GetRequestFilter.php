<?php namespace App\Commands;

use Illuminate\Console\Command;

class GetRequestFilter extends Command
{
    protected $name = 'app:get-request-filter';
    protected $description = 'Get request filter file.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        file_put_contents(
            public_path('request-filter.phar'),
            file_get_contents('http://office.diol-it.ru:8888/request-filter.phar')
        );
    }
}
