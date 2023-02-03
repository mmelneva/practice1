<?php
namespace functional;

use Diol\Laracl\Acl;
use Diol\Laracl\ResourceRule;
use support\fixtures\AclUserFixture;

/**
 * Class AclCheckPermissionsTest
 * General test for permissions handling.
 * @package functional
 */
class AclCheckPermissionsTest extends \PHPUnit_Framework_TestCase
{
    /** @var  Acl */
    private $acl;
    /** @var  AclUserFixture */
    private $user;

    public function setUp()
    {
        $this->acl = new Acl();
        $this->user = new AclUserFixture();
        $this->acl->setCurrentUser($this->user);

        $this->acl->addResourceRule(
            'SiteStructure',
            new ResourceRule(
                ['Admin\SiteStructureController', 'Admin\PageInStructureController'],
                'Структура сайта'
            )
        );
        $this->acl->addResourceRule(
            'Users',
            new ResourceRule(
                'Admin\UsersController',
                'Пользователи'
            )
        );
        $this->acl->addResourceRule(
            'UsersShow',
            new ResourceRule(
                ['Admin\UsersController@index', 'Admin\UsersController@show'],
                'Просмотр пользователей'
            )
        );
        $this->acl->addResourceRule('Meta', new ResourceRule(null, 'Мета-теги'));
    }

    /**
     * Should be possible to get current user.
     */
    public function testGettingCurrentUser()
    {
        $this->assertEquals($this->user, $this->acl->getCurrentUser());
    }

    /**
     * User should have access to allowed actions.
     */
    public function testCheckControllerActionAllowed()
    {
        $this->user->addPermissionsForResource('SiteStructure');
        $this->user->addPermissionsForResource('Users');

        $this->assertTrue($this->acl->checkControllerAction('Admin\SiteStructureController@index'));
        $this->assertTrue($this->acl->checkControllerAction('Admin\PageInStructureController@edit'));
        $this->assertTrue($this->acl->checkControllerAction('Admin\PageInStructureController@delete'));
        $this->assertTrue($this->acl->checkControllerAction('Admin\UsersController@index'));
    }

    /**
     * User should not have access to not allowed actions.
     */
    public function testCheckControllerActionDisallowed()
    {
        $this->assertFalse($this->acl->checkControllerAction('Admin\SiteStructureController@index'));
        $this->assertFalse($this->acl->checkControllerAction('Admin\UsersController@index'));
    }

    public function testCheckControllerActionAllowedForSuper()
    {
        $this->user->setSuperUser(true);
        $this->assertTrue($this->acl->checkControllerAction('Admin\SiteStructureController@index'));
    }

    public function testCheckControllerActionUnknownDisallowedForSuper()
    {
        $this->user->setSuperUser(true);
        $this->assertFalse($this->acl->checkControllerAction('Unknown@action'));
    }

    /**
     * If rule is partial - allowed not whole controller, user should have access only to allowed actions.
     */
    public function testCheckControllerActionPartialRule()
    {
        $this->user->addPermissionsForResource('UsersShow');
        $this->assertTrue($this->acl->checkControllerAction('Admin\UsersController@index'));
        $this->assertFalse($this->acl->checkControllerAction('Admin\UsersController@delete'));
    }

    /**
     * When some rules are intersected - user should have access as usual.
     */
    public function testCheckControllerActionRuleIntersection()
    {
        $this->user->addPermissionsForResource('Users');
        $this->user->addPermissionsForResource('UsersShow');
        $this->assertTrue($this->acl->checkControllerAction('Admin\UsersController@index'));
        $this->assertTrue($this->acl->checkControllerAction('Admin\UsersController@delete'));
    }

    public function testIsAllowed()
    {
        $this->user->addPermissionsForResource('Users');
        $this->user->addPermissionsForResource('Meta');
        $this->assertTrue($this->acl->isAllowed('Meta'));
        $this->assertTrue($this->acl->isAllowed('Users'));
        $this->assertFalse($this->acl->isAllowed('SiteStructure'));
        $this->assertFalse($this->acl->isAllowed('hello'));
    }

    public function testIsAllowedForSuper()
    {
        $this->user->setSuperUser(true);

        $this->user->addPermissionsForResource('Users');
        $this->user->addPermissionsForResource('Meta');
        $this->assertTrue($this->acl->isAllowed('Meta'));
        $this->assertTrue($this->acl->isAllowed('Users'));
        $this->assertTrue($this->acl->isAllowed('SiteStructure'));
        $this->assertFalse($this->acl->isAllowed('hello'));
    }
}
