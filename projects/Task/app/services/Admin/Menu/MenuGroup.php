<?php namespace App\Services\Admin\Menu;

/**
 * Class MenuGroup
 * Menu group container.
 * @package App\Services\Admin\Menu
 */
class MenuGroup
{
    const SORT_ASC = 1;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $icon;

    /**
     * @var int
     */
    private $sorting;

    /**
     * @var MenuElement[]
     */
    private $menuElementList = [];

    /**
     * Create menu group.
     *
     * @param string $name - menu group name.
     * @param string $icon - menu group icon.
     * @param int $sorting - way to sort elements.
     */
    public function __construct($name, $icon, $sorting = null)
    {
        $this->name = $name;
        $this->icon = $icon;
        $this->sorting = $sorting;
    }

    /**
     * Add menu element to group.
     *
     * @param MenuElement $menuElement
     */
    public function addMenuElement(MenuElement $menuElement)
    {
        $this->menuElementList[] = $menuElement;
    }

    /**
     * Get menu group name.
     *
     * @return string.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get menu group icon.
     *
     * @return string.
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Get menu element list.
     *
     * @return MenuElement[]
     */
    public function getMenuElementList()
    {
        $elementList = $this->menuElementList;
        if ($this->sorting == self::SORT_ASC) {
            usort(
                $elementList,
                function (MenuElement $_1, MenuElement $_2) {
                    if ($_1->getName() > $_2->getName()) {
                        return 1;
                    } elseif ($_1->getName() < $_2->getName()) {
                        return -1;
                    } else {
                        return 0;
                    }
                }
            );
        }

        return $elementList;
    }
}
