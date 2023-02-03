<?php
namespace support\fixtures;

use Diol\Laracl\IAclUser;

/**
 * Class AclUserFixture
 * Fixture class. Emulates user model.
 * @package support\fixtures
 */
class AclUserFixture implements IAclUser
{
    /**
     * @var bool
     */
    private $superUser = false;

    /**
     * @var array
     */
    private $resourcePermissions;

    /**
     * Create user fixture.
     */
    public function __construct()
    {
        $this->resourcePermissions = [];
    }

    /**
     * @param bool $superUser
     */
    public function setSuperUser($superUser)
    {
        $this->superUser = $superUser;
    }

    /**
     * Add rule identifier
     * @param $resource
     */
    public function addPermissionsForResource($resource)
    {
        $this->resourcePermissions[] = $resource;
    }

    /**
     * {@inheritDoc}
     */
    public function getRuleIdentifiers()
    {
        return $this->resourcePermissions;
    }

    /**
     * User is super user.
     * @return bool
     */
    public function isSuper()
    {
        return $this->superUser;
    }
}
