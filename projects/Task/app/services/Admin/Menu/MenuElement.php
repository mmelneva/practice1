<?php namespace App\Services\Admin\Menu;

/**
 * Class MenuElement
 * Menu element container.
 * @package App\Services\Admin\Menu
 */
class MenuElement
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $icon;

    /**
     * @var string
     */
    private $action;

    /**
     * @var array
     */
    private $controllerList;

    /**
     * Create menu element.
     *
     * @param string $name
     * @param string $icon
     * @param string $action
     * @param array $controllerList
     */
    public function __construct($name, $icon, $action, array $controllerList)
    {
        $this->name = $name;
        $this->icon = $icon;
        $this->action = $action;
        $this->controllerList = $controllerList;
    }

    /**
     * Get menu element action.
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Get menu element controller list.
     *
     * @return array
     */
    public function getControllerList()
    {
        return $this->controllerList;
    }

    /**
     * Get menu element icon.
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Get menu element list.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
