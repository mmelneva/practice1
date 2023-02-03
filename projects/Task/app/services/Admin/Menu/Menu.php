<?php namespace App\Services\Admin\Menu;

/**
 * Class Menu
 * Wrapper for menu items.
 * @package App\Services\Admin\Menu
 */
class Menu
{
    /**
     * @var array
     */
    private $menuItems = [];

    /**
     * Add menu element.
     *
     * @param MenuElement $menuElement
     */
    public function addMenuElement(MenuElement $menuElement)
    {
        $this->menuItems[] = $menuElement;
    }

    /**
     * Add group of menu elements.
     *
     * @param MenuGroup $menuGroup
     */
    public function addMenuGroup(MenuGroup $menuGroup)
    {
        $this->menuItems[] = $menuGroup;
    }

    /**
     * Get list of menu elements and groups of elements.
     *
     * @return array
     */
    public function getMenuItems()
    {
        return $this->menuItems;
    }

    /**
     * Get flatten menu.
     *
     * @return array
     */
    public function getFlattenMenu()
    {
        $flattenMenu = [];
        foreach ($this->menuItems as $menuItem) {
            if ($menuItem instanceof MenuElement) {
                $flattenMenu[] = $menuItem;
            } elseif ($menuItem instanceof MenuGroup) {
                foreach ($menuItem->getMenuElementList() as $menuElement) {
                    $flattenMenu[] = $menuElement;
                }
            }
        }
        return $flattenMenu;
    }
}
