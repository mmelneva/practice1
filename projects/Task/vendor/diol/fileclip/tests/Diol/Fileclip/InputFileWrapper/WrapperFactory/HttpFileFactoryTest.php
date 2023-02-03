<?php
namespace Diol\Fileclip\InputFileWrapper\WrapperFactory;

class HttpFileFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var  HttpFileFactory */
    private $factory;

    public function setUp()
    {
        parent::setUp();
        $this->factory = new HttpFileFactory();
    }

    /**
     * @param $value
     * @dataProvider validInputDataProvider
     */
    public function testValidInputValues($value)
    {
        $this->assertTrue($this->factory->check($value));
    }

    public function validInputDataProvider()
    {
        return [
            ['http://hello'],
            ['https://hello'],
        ];
    }

    /**
     * @param $value
     * @dataProvider invalidInputDataProvider
     */
    public function testInvalidInputValues($value)
    {
        $this->assertFalse($this->factory->check($value));
    }

    public function invalidInputDataProvider()
    {
        return [
            ['htt://hello'],
            ['Xhttp://hello'],
            ['https:/hello'],
            ['test'],
            [new \stdClass()],
            ['ftp://hello.org']
        ];
    }

    public function testWrapperCreation()
    {
        $this->assertInstanceOf(
            'Diol\Fileclip\InputFileWrapper\Wrapper\HttpFile',
            $this->factory->getWrapper('http://example.com')
        );
    }
}
