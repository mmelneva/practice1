<?php
namespace unit;

use Diol\Laracl\ResourceRule;
use support\UnitTestCase;

class ResourceRuleTest extends UnitTestCase
{
    private function getResourceRule(array $options = [])
    {
        if (!isset($options['actions'])) {
            $options['actions'] = ['Admin\SiteStructureController', 'Admin\PageInStructureController'];
        }
        if (!isset($options['name'])) {
            $options['name'] = 'Структура сайта';
        }

        return new ResourceRule($options['actions'], $options['name']);
    }

    public function testGetName()
    {
        $this->assertEquals('Структура сайта', $this->getResourceRule()->getName());
    }

    public function testGetActions()
    {
        $this->assertEquals(
            ['Admin\SiteStructureController', 'Admin\PageInStructureController'],
            $this->getResourceRule()->getActions()
        );
    }

    public function testGetActionsWhenNotArray()
    {
        $this->assertEquals(
            ['Admin\SiteStructureController'],
            $this->getResourceRule(['actions' => 'Admin\SiteStructureController'])->getActions()
        );
    }
}
