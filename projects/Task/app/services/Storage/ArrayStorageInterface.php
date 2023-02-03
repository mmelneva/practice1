<?php namespace App\Services\Storage;

interface ArrayStorageInterface
{
    /**
     * Get items from storage.
     * @return mixed
     */
    public function items();

    /**
     * Save items to storage.
     * @param $items
     */
    public function save($items);
}
