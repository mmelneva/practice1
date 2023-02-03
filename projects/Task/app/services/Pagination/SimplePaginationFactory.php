<?php namespace App\Services\Pagination;

class SimplePaginationFactory
{
    public function make(array $byPageStructure)
    {
        \Paginator::setCurrentPage($byPageStructure['page']);

        $paginator = \Paginator::make(
            $byPageStructure['items']->all(),
            $byPageStructure['total'],
            $byPageStructure['limit']
        );

        return $paginator;
    }
}
