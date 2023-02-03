<?php
namespace unit;

use Diol\Laracl\Acl;
use support\UnitTestCase;

class AclTest extends UnitTestCase
{
    /** @var  Acl */
    private $acl;
    /** @var  \Mockery\MockInterface */
    private $user;

    public function setUp()
    {
        parent::setUp();

        $this->acl = new Acl();
        $this->user = \Mockery::mock('Diol\Laracl\IAclUser');
        $this->user->shouldReceive('getRuleIdentifiers')->andReturn([])->byDefault();
        $this->user->shouldReceive('isSuper')->andReturn(false);
        $this->acl->setCurrentUser($this->user);
    }

    /**
     * @expectedException \Diol\Laracl\Acl\NoCurrentUser
     */
    public function testUserIsNotDefined()
    {
        $acl = new Acl();
        $acl->checkControllerAction('controller@action');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider invalidActionFormatDataProvider
     */
    public function testActionFormat($invalidControllerAction)
    {
        $this->acl->checkControllerAction($invalidControllerAction);
    }

    /**
     * Data provider - list of invalid action variants.
     * @return array
     */
    public function invalidActionFormatDataProvider()
    {
        return [
            ['controller'],
            ['controller@action@action'],
            ['controller@'],
            ['@action']
        ];
    }

    public function testStrictDisallowedWhenNoRule()
    {
        $strictAcl = new Acl();
        $strictAcl->setCurrentUser($this->user);
        $this->assertFalse($strictAcl->checkControllerAction('controller1@index'));
    }

    public function testNotStrictAllowedWhenNoRule()
    {
        $notStrictAcl = new Acl(false);
        $notStrictAcl->setCurrentUser($this->user);
        $this->assertTrue($notStrictAcl->checkControllerAction('controller1@index'));
    }

    public function testAllowedExactMatch()
    {
        $this->user->shouldReceive('getRuleIdentifiers')->andReturn(['r1', 'r2']);

        $rule = \Mockery::mock('Diol\Laracl\ResourceRule');
        $rule->shouldReceive('getName')->andReturn('Test resource');
        $rule->shouldReceive('getActions')->andReturn(['controller1@index', 'controller1@edit']);
        $this->acl->addResourceRule('r1', $rule);

        $this->assertTrue($this->acl->checkControllerAction('controller1@index'));
    }

    public function testAllowedPartialMatch()
    {
        $this->user->shouldReceive('getRuleIdentifiers')->andReturn(['r1', 'r2']);

        $rule = \Mockery::mock('Diol\Laracl\ResourceRule');
        $rule->shouldReceive('getName')->andReturn('Test resource');
        $rule->shouldReceive('getActions')->andReturn(['controller1']);
        $this->acl->addResourceRule('r1', $rule);

        $this->assertTrue($this->acl->checkControllerAction('controller1@index'));
    }

    public function testDisallowed()
    {
        $this->user->shouldReceive('getRuleIdentifiers')->andReturn([]);

        $rule = \Mockery::mock('Diol\Laracl\ResourceRule');
        $rule->shouldReceive('getName')->andReturn('Test resource');
        $rule->shouldReceive('getActions')->andReturn(['controller1']);
        $this->acl->addResourceRule('r1', $rule);

        $this->assertFalse($this->acl->checkControllerAction('controller1@index'));
    }

    public function testAllowedWhenRulesIntersected()
    {
        $this->user->shouldReceive('getRuleIdentifiers')->andReturn(['r1']);

        $rule = \Mockery::mock('Diol\Laracl\ResourceRule');
        $rule->shouldReceive('getName')->andReturn('Test resource');
        $rule->shouldReceive('getActions')->andReturn(['controller1']);
        $this->acl->addResourceRule('r1', $rule);

        $intersectedRule = \Mockery::mock('Diol\Laracl\ResourceRule');
        $intersectedRule->shouldReceive('getName')->andReturn('Test resource');
        $intersectedRule->shouldReceive('getActions')->andReturn(['controller1@edit']);
        $this->acl->addResourceRule('r2', $intersectedRule);

        $this->assertTrue($this->acl->checkControllerAction('controller1@index'));
        $this->assertTrue($this->acl->checkControllerAction('controller1@edit'));
    }

    /**
     * @expectedException \Diol\Laracl\Acl\IdentifierCollision
     */
    public function testIdentifierCollision()
    {
        $rule1 = \Mockery::mock('Diol\Laracl\ResourceRule');
        $rule2 = \Mockery::mock('Diol\Laracl\ResourceRule');

        $this->acl->addResourceRule('1', $rule1);
        $this->acl->addResourceRule('1', $rule2);
    }
}
