<?php
namespace Diol\Laracl;

use Diol\Laracl\Acl\IdentifierCollision;
use Diol\Laracl\Acl\NoCurrentUser;

/**
 * Class Acl
 * ACL-class to manage permissions.
 * @package Diol\Laracl
 */
class Acl
{
    /**
     * Current user.
     * @var IAclUser
     */
    private $user;

    /**
     * List of resource rules.
     * @var ResourceRule[]
     */
    private $resourceRules;

    /**
     * Strict flag.
     * @var bool
     */
    private $strict;

    /**
     * Create new object to manage ACL
     * @param bool $strict - if strict, all unknown controllers and actions will be dissalowed.
     */
    public function __construct($strict = true)
    {
        $this->resourceRules = [];
        $this->strict = $strict;
    }

    /**
     * Set current user.
     * @param IAclUser $user
     */
    public function setCurrentUser(IAclUser $user)
    {
        $this->user = $user;
    }

    /**
     * Get current user.
     * @return IAclUser
     */
    public function getCurrentUser()
    {
        return $this->user;
    }

    /**
     *  Add resource rule.
     * @param $identifier
     * @param ResourceRule $resourceRule
     * @throws Acl\IdentifierCollision
     */
    public function addResourceRule($identifier, ResourceRule $resourceRule)
    {
        if (isset($this->resourceRules[$identifier])) {
            throw new IdentifierCollision('Identifier collision.');
        }
        $this->resourceRules[$identifier] = $resourceRule;
    }

    /**
     * Get list of existing resource rules.
     * @return ResourceRule[]
     */
    public function getResourceRuleList()
    {
        return $this->resourceRules;
    }

    /**
     * Remove resource rule by identifier.
     * @param $identifier
     */
    public function removeResourceRule($identifier)
    {
        if (isset($this->resourceRules[$identifier])) {
            unset($this->resourceRules[$identifier]);
        }
    }

    /**
     * Get list of enabled resource rules according to current user.
     * @return ResourceRule[]
     * @throws Acl\NoCurrentUser
     */
    public function getEnabledResourceRuleList()
    {
        if (is_null($this->user)) {
            throw new NoCurrentUser("User for ACL is not defined. Use method setCurrentUser to define it.");
        }
        /** @var ResourceRule[] $enabledResources */
        $enabledResources = [];

        if ($this->user->isSuper()) {
            $enabledResources = $this->resourceRules;
        } else {
            foreach ($this->resourceRules as $identifier => $resourceRule) {
                if (in_array($identifier, $this->user->getRuleIdentifiers())) {
                    $enabledResources[$identifier] = $resourceRule;
                }
            }
        }

        return $enabledResources;
    }

    /**
     * Check controller action.
     * @param string $checkControllerAction - controller action, examples: controller@action.
     * @return bool
     * @throws \InvalidArgumentException
     * @throws Acl\NoCurrentUser
     */
    public function checkControllerAction($checkControllerAction)
    {
        if (is_null($this->user)) {
            throw new NoCurrentUser("User for ACL is not defined. Use method setCurrentUser to define it.");
        }

        if (!preg_match("/^[^@]+@[^@]+$/", $checkControllerAction)) {
            throw new \InvalidArgumentException("Invalid controller action format. Example: controller@action.");
        }

        $collectedRules = [];

        list($checkController, $checkAction) = explode('@', $checkControllerAction);
        foreach ($this->resourceRules as $identifier => $rule) {
            foreach ($rule->getActions() as $resourceControllerAction) {
                $expResourceControllerAction = explode('@', $resourceControllerAction);

                $resourceController = $expResourceControllerAction[0];
                if (count($expResourceControllerAction) == 2) {
                    $resourceAction = $expResourceControllerAction[1];
                } else {
                    $resourceAction = null;
                }

                if ($resourceController == $checkController
                    && (is_null($resourceAction) || $resourceAction == $checkAction)
                ) {
                    $collectedRules[] = $identifier;
                }
            }
        }

        if (count($collectedRules) === 0) {
            return !$this->strict;
        } else {
            if ($this->user->isSuper()) {
                $userRuleIdentifiers = array_keys($this->resourceRules);
            } else {
                $userRuleIdentifiers = $this->user->getRuleIdentifiers();
            }

            foreach ($userRuleIdentifiers as $ruleId) {
                if (in_array($ruleId, $collectedRules)) {
                    return true;
                }
            }

            return false;
        }
    }

    /**
     * Check if current user is allowed to that rule.
     * @param $ruleIdentity
     * @return bool
     */
    public function isAllowed($ruleIdentity)
    {
        $ruleIdList = array_keys($this->resourceRules);
        $inCurrentRules = in_array($ruleIdentity, $ruleIdList);
        $inUserRules = in_array($ruleIdentity, $this->user->getRuleIdentifiers());

        return $inCurrentRules && ($inUserRules || $this->user->isSuper());
    }
}
