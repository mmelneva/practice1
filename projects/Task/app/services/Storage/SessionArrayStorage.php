<?php namespace App\Services\Storage;

use \Session;

/**
 * Class SessionArrayStorage
 * @package App\Services\Storage
 */
class SessionArrayStorage implements ArrayStorageInterface
{
    /** @var string */
    private $key;

    /**
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * Get cart items form session.
     *
     * @return string
     */
    public function items()
    {
        $items = \Session::get($this->key);
        return $this->decode($items);
    }

    /**
     * Save cart items to session.
     *
     * @param $items
     * @throws \InvalidArgumentException
     */
    public function save($items)
    {
        if (is_array($items)) {
            \Session::set($this->key, $this->encode($items));
        } else {
            throw new \InvalidArgumentException("Argument 'items' must be an array.");
        }
    }

    /**
     * Encode items before set to session.
     *
     * @param array $items
     * @return string
     */
    protected function encode(array $items)
    {
        return json_encode($items);
    }

    /**
     * Decode after getting encoded items from session.
     *
     * @param string $encoded
     * @return mixed
     */
    protected function decode($encoded)
    {
        if (is_string($encoded)) {
            $decoded = json_decode($encoded);
        }

        return isset($decoded) && is_array($decoded) ? $decoded : [];
    }
}
