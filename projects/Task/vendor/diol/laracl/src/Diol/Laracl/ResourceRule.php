<?php
namespace Diol\Laracl;

/**
 * Class ResourceRule
 * Rule for ACL.
 * @package Diol\Laracl
 */
class ResourceRule
{

    /**
     * @var array
     */
    private $actions;

    /**
     * @var string
     */
    private $name;

    /**
     * Create a rule.
     * @param $actions - array|string.
     * @param string $name
     */
    public function __construct($actions, $name = '')
    {
        $this->actions = is_array($actions) ? $actions : [$actions];
        $this->name = $name;
    }

    /**
     * Get array of actions.
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Get name of the rule.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
