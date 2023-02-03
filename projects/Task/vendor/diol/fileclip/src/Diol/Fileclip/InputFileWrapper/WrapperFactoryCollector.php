<?php
namespace Diol\Fileclip\InputFileWrapper;

use Diol\Fileclip\InputFileWrapper\Wrapper\NullWrapper;

/**
 * Class WrapperFactoryCollector
 * Wrapper factory collector to manage different types of wrappers
 * @package Diol\Fileclip\InputFileWrapper
 */
class WrapperFactoryCollector
{
    /**
     * @var IWrapperFactory[]
     */
    private $wrapperFactories = [];

    /**
     * Add wrapper factory
     * @param IWrapperFactory $factory
     */
    public function addWrapperFactory(IWrapperFactory $factory)
    {
        $this->wrapperFactories[] = $factory;
    }

    /**
     * Get wrapper according to collected wrapper factories
     * @param $value
     * @return IWrapper
     */
    public function getWrapper($value)
    {
        foreach ($this->wrapperFactories as $factory) {
            if ($factory->check($value)) {
                return $factory->getWrapper($value);
            }
        }
        return new NullWrapper();
    }
}
