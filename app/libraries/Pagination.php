<?php
class Pagination
{
    private $totalItems;
    private $itemsPerPage;
    private $currentPage;
    private $totalPages;

    public function __construct($totalItems, $itemsPerPage = 8)
    {
        $this->totalItems = $totalItems;
        $this->itemsPerPage = $itemsPerPage;
        $this->totalPages = ceil($this->totalItems / $this->itemsPerPage);
        $this->setCurrentPage();
    }

    private function setCurrentPage()
    {
        $this->currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $this->currentPage = max(1, min($this->currentPage, $this->totalPages));
    }

    public function getOffset()
    {
        return ($this->currentPage - 1) * $this->itemsPerPage;
    }

    public function getLimit()
    {
        return $this->itemsPerPage;
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function getTotalPages()
    {
        return $this->totalPages;
    }

    public function getStartIndex()
    {
        return $this->getOffset() + 1;
    }

    public function hasNextPage()
    {
        return $this->currentPage < $this->totalPages;
    }

    public function hasPreviousPage()
    {
        return $this->currentPage > 1;
    }
}
