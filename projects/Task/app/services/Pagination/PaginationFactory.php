<?php namespace App\Services\Pagination;

/**
 * Class PaginationFactory
 * @package PrettyPagination
 */
class PaginationFactory
{
    /**
     * Pretty prefix for page urls.
     * @var string
     */
    private $prettyPrefix;

    /**
     * @param string $prettyPrefix
     */
    public function __construct($prettyPrefix = 'page-')
    {
        $this->prettyPrefix = $prettyPrefix;
    }

    /**
     * @param callable $urlCallback - callback to make proper urls.
     * @param $items - items on page.
     * @param $total - total items count
     * @param $currentPage
     * @param $limit - items count on page.
     * @return Pagination
     * @throws PaginationException
     */
    public function getPagination(callable $urlCallback, $items, $total, $currentPage, $limit)
    {
        $lastPage = ceil($total / $limit);

        if ($currentPage < 1) {
            \App::abort(404, 'Current page has incorrect value.');
        }
        if ($lastPage != 0 && $currentPage > $lastPage) {
            \App::abort(404, 'Current page is bigger than last page.');
        }

        return new Pagination($this->prettyPrefix, $items, $total, $currentPage, $lastPage, $urlCallback);
    }
}
