<?php namespace App\Commands;

use Illuminate\Console\Command;

/**
 * Class SiteMapGenerate
 * @package App\Commands
 */
class SitemapGenerate extends Command
{
    protected $name = 'app:sitemap-generate';

    protected $description = 'Generate site map.';

    public function fire()
    {
        \App::make('App\Services\Sitemap\SitemapGenerator')->generate();
    }
}
