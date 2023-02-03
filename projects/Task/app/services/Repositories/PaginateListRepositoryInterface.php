<?php namespace App\Services\Repositories;

interface PaginateListRepositoryInterface extends ListRepositoryInterface
{
    public function byPage($page = 1, $limit = 20);
}
