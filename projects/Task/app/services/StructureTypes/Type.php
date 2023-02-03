<?php
namespace App\Services\StructureTypes;

/**
 * Class Type
 * @package App\Services\StructureTypes
 */
class Type
{
    private $name;
    private $unique;
    private $repoKey;
    private $clientUrlCreator;

    /**
     * @param $name - type name.
     * @param $unique - if type is unique.
     * @param $repoKey - key to get repo.
     * @param callable $clientUrlCreator
     */
    public function __construct($name, $unique, $repoKey, callable $clientUrlCreator)
    {
        $this->name = $name;
        $this->unique = $unique;
        $this->repoKey = $repoKey;
        $this->clientUrlCreator = $clientUrlCreator;
    }

    /**
     * @return mixed
     */
    public function getRepoKey()
    {
        return $this->repoKey;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getUnique()
    {
        return $this->unique;
    }

    /**
     * @return callable
     */
    public function getClientUrlCreator()
    {
        return $this->clientUrlCreator;
    }
}
