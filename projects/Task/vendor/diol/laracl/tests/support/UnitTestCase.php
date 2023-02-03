<?php
namespace support;

/**
 * Class UnitTestCase
 * Abstract class for unit-tests.
 * @package support
 */
abstract class UnitTestCase extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        \Mockery::close();
    }
}
