<?php
namespace functional;

use Diol\Laracl\Acl;
use Diol\Laracl\ResourceRule;
use support\fixtures\AclUserFixture;

/**
 * Class AclRuleResourceManagementTest
 * Test resource rule management in ACL.
 * @package functional
 */
class AclRuleResourceManagementTest extends \PHPUnit_Framework_TestCase
{
    /** @var  Acl */
    private $acl;

    /**
     * @var ResourceRule[]
     */
    private $rules;

    public function setUp()
    {
        parent::setUp();

        $this->acl = new Acl();
        $this->rules = [
            new ResourceRule('controller@action'),
            new ResourceRule('controller@action'),
        ];

        $this->acl->addResourceRule('rule1', $this->rules[0]);
        $this->acl->addResourceRule('rule2', $this->rules[1]);
    }

    /**
     * Acl should return all received resource rules.
     */
    public function testGetResourceRules()
    {
        $this->assertEquals(
            ['rule1' => $this->rules[0], 'rule2' => $this->rules[1]],
            $this->acl->getResourceRuleList()
        );
    }

    /**
     * Acl should not return deleted resource rule.
     */
    public function testDeleteResourceRule()
    {
        $this->acl->removeResourceRule('rule1');
        $this->acl->removeResourceRule('not found rule');

        $this->assertEquals(['rule2' => $this->rules[1]], $this->acl->getResourceRuleList());
    }

    /**
     * Acl should return correct list of enabled resources for user.
     */
    public function testEnabledForUserResources()
    {
        $user1 = new AclUserFixture();
        $user1->addPermissionsForResource('rule1');
        $user1->addPermissionsForResource('rule2');
        $this->acl->setCurrentUser($user1);
        $this->assertEquals(
            ['rule1' => $this->rules[0], 'rule2' => $this->rules[1]],
            $this->acl->getEnabledResourceRuleList()
        );


        $user2 = new AclUserFixture();
        $user2->addPermissionsForResource('rule1');
        $this->acl->setCurrentUser($user2);
        $this->assertEquals(['rule1' => $this->rules[0]], $this->acl->getEnabledResourceRuleList());

        $user3 = new AclUserFixture();
        $this->acl->setCurrentUser($user3);
        $this->assertEquals([], $this->acl->getEnabledResourceRuleList());
    }

    /**
     * All resource rules should be always enabled for super user.
     */
    public function testEnabledForSuperUserResources()
    {
        $superUser = new AclUserFixture();
        $superUser->setSuperUser(true);
        $this->acl->setCurrentUser($superUser);
        $this->assertEquals(
            ['rule1' => $this->rules[0], 'rule2' => $this->rules[1]],
            $this->acl->getEnabledResourceRuleList()
        );
    }

    /**
     * When there are no user, exception should be thrown.
     * @expectedException \Diol\Laracl\Acl\NoCurrentUser
     */
    public function testEnabledResourcesForNonUser()
    {
        $this->acl->getEnabledResourceRuleList();
    }
}
