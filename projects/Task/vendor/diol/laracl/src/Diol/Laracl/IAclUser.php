<?php
namespace Diol\Laracl;

/**
 * Interface IAclUser
 * This interface should implement user model on the site.
 * @package Diol\Laracl
 */
interface IAclUser
{
    /**
     * Get list of rule identifiers for this user. (allowed rules).
     * @return mixed
     */
    public function getRuleIdentifiers();

    /**
     * User is super user.
     * @return bool
     */
    public function isSuper();
}
