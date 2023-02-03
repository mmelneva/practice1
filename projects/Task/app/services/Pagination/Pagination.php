<?php namespace App\Services\Pagination;

/**
 * Class Pagination
 * Class-container to make pretty and flexible pagination.
 * @package PrettyPagination
 */
class Pagination
{
    /**
     * Pretty prefix for page urls.
     * @var string
     */
    private $prettyPrefix;
    /**
     * @var mixed - items list.
     */
    private $items;
    /**
     * @var int - total count of items (not only received items).
     */
    private $total;
    /**
     * @var int - current page number.
     */
    private $currentPage;
    /**
     * @var int - last page number.
     */
    private $lastPage;
    /**
     * @var int - number of links on one page.
     */
    private $pageLinksLimit;
    /**
     * @var callable - callback to create proper links for pages.
     */
    private $urlCallback;

    /**
     * Create pagination container.
     * @param string $prettyPrefix - prefix for page urls.
     * @param mixed $items - items list.
     * @param int $total - total count of items (not only received items).
     * @param int $currentPage - current page number.
     * @param int $lastPage - last page number.
     * @param callable $urlCallback - number of links on one page.
     */
    public function __construct($prettyPrefix, $items, $total, $currentPage, $lastPage, callable $urlCallback)
    {
        $this->prettyPrefix = $prettyPrefix;
        $this->items = $items;
        $this->total = $total;
        $this->currentPage = $currentPage;
        $this->lastPage = $lastPage;
        $this->pageLinksLimit = 7;
        $this->urlCallback = $urlCallback;
    }

    /**
     * Get items.
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Get total count of items (not only stored).
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Get current page number.
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * Get last page number.
     * @return int
     */
    public function getLastPage()
    {
        return $this->lastPage;
    }

    /**
     * Get previous page number. null if there is no previous page.
     * @return int|null
     */
    public function getPreviousPage()
    {
        return $this->currentPage > 1 ? $this->currentPage - 1 : null;
    }

    /**
     * Get next page number. null if there is no next page.
     * @return int|null
     */
    public function getNextPage()
    {
        return $this->currentPage < $this->lastPage ? $this->currentPage + 1 : null;
    }

    /**
     * Get url for page.
     * @param $page
     * @return mixed
     */
    public function getUrl($page)
    {
        $urlCallback = $this->urlCallback;

        return $urlCallback($page, $this->prettyPrefix);
    }

    /**
     * Get an array of page links with numbers.
     * @return array
     */
    public function getPageUrlList()
    {
        $pagesArray = [];
        list($startPage, $endPage) = $this->generatePagesLimits();
        for ($i = $startPage; $i <= $endPage; $i += 1) {
            $pageElement = new \stdClass;
            $pageElement->number = $i;
            $pageElement->url = $this->getUrl($i);
            $pagesArray[] = $pageElement;
        }

        return $pagesArray;
    }

    /**
     * Generate limits for pagination (page's numeration starts from 1)
     *
     * @return array: 0 => lower limit, 1 => upper limit
     */
    private function generatePagesLimits()
    {
        $min = $this->currentPage - floor($this->pageLinksLimit / 2);
        if ($min < 1) {
            $min = 1;
        }
        $toMin = $this->currentPage - $min;

        $max = $this->currentPage + floor($this->pageLinksLimit / 2);
        if ($max > $this->lastPage) {
            $max = $this->lastPage;
        }
        $toMax = $max - $this->currentPage;

        $residue = $this->pageLinksLimit - $toMin - $toMax - 1;

        if ($min == 1) {
            $max = $max + $residue;
            if ($max > $this->lastPage) {
                $max = $this->lastPage;
            }
        } else {
            if ($max == $this->lastPage) {
                $min = $min - $residue;
                if ($min < 1) {
                    $min = 1;
                }
            }
        }

        return [$min, $max];
    }
}
