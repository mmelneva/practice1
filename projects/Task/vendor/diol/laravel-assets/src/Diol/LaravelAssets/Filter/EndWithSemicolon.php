<?php namespace Diol\LaravelAssets\Filter;

use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;

/**
 * Class AddSemicolonFilter
 * @package Diol\LaravelAssets\Filter
 */
class EndWithSemicolon implements FilterInterface
{
    /**
     * @@inheritdoc
     */
    public function filterLoad(AssetInterface $asset)
    {
        // nothing
    }

    public function filterDump(AssetInterface $asset)
    {
        $asset->setContent($this->addSemicolon($asset->getContent()));
    }

    private function addSemicolon($content)
    {
        return "$content;";
    }
}
