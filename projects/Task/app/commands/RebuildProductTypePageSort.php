<?php namespace App\Commands;

use Illuminate\Console\Command;

class RebuildProductTypePageSort extends Command
{
    protected $name = 'app:rebuild-product-type-page-sort';
    protected $description = 'Rebuild product type page random sort for all the product types.';

    public function fire()
    {
        $lockFile = storage_path('meta/rebuild-product-type-page-sort.lock');
        $lockHandler = fopen($lockFile, 'w');
        if (flock($lockHandler, LOCK_EX | LOCK_NB)) {
            $productTypePageSort = \App::make('App\Services\Cache\ProductTypePageSortCache');
            $productTypePageSort->rebuildAll();
            flock($lockHandler, LOCK_UN);
        }
        fclose($lockHandler);
    }
}
