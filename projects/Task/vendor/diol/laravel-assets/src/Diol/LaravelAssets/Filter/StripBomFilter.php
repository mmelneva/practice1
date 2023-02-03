<?php namespace Diol\LaravelAssets\Filter;

use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;

class StripBomFilter implements FilterInterface
{
    /**
     * @@inheritdoc
     */
    public function filterLoad(AssetInterface $asset)
    {
        // nothing
    }

    /**
     * @@inheritdoc
     */
    public function filterDump(AssetInterface $asset)
    {
        $asset->setContent($this->stripBom($asset->getContent()));
    }

    /**
     * Strip Utf-8 BOM from content.
     *
     * @param $content
     * @return mixed
     */
    private function stripBom($content)
    {
        $bom = pack('H*', 'EFBBBF');
        $content = preg_replace("/^{$bom}/", '', $content);
        return $content;
    }
}