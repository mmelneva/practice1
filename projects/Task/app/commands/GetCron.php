<?php namespace App\Commands;

use Illuminate\Console\Command;

/**
 * Class GetCron
 * @package App\Command
 */
class GetCron extends Command
{
    protected $name = 'app:get-cron';

    protected $description = 'Get commands for cron';

    public function fire()
    {
        $callArtisan = PHP_BINARY . ' ' . base_path('artisan');

        $taskList = [];
        $taskList[] = "13 * * * *    {$callArtisan} app:sitemap-generate";
        $taskList[] = "0 * * * *    {$callArtisan} app:recount-product-type-pages";

        $cronString = implode("\n", $taskList);
        $companyName = \Config::get('settings.company_name');
        $this->info("# {$companyName} cron tasks - BEGIN\n\n" . $cronString . "\n\n# {$companyName} cron tasks - END");
    }
}
