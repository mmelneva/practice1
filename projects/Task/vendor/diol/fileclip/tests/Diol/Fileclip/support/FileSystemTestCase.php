<?php
namespace Diol\Fileclip\support;

abstract class FileSystemTestCase extends \PHPUnit_Framework_TestCase
{
    use FilesystemTestTools;

    public function setUp()
    {
        $this->recreateTestStorage();
        $this->recreateTestPublic();
    }

    public function tearDown()
    {
        $this->removeTestStorage();
        $this->removeTestPublic();
    }
}
