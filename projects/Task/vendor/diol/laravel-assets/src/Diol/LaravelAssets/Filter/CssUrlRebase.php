<?php namespace Diol\LaravelAssets\Filter;

use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;

/**
 * Class CssUrlRebase
 * Rebase url's in css to absolute pathes.
 *
 * @package Diol\LaravelAssets\Filter
 */
class CssUrlRebase implements FilterInterface
{
    const SEARCH_PATTERN = "%url\\(['\" ]*(?<path>(?!data:|//)[^'\"#\?: ]+)(?<query>(\?([^#]*))?(#(.*))?)['\" ]*\\)%U";
    const URI_PATTERN = "url(%s)";

    /**
     * @var string
     */
    private $rootPath;

    public function __construct($root = null)
    {
        if (is_null($root)) {
            $root = public_path();
        }
        if (!is_dir($root)) {
            throw new \InvalidArgumentException("Directory {$root} does not exist");
        }

        $this->rootPath = realpath($root);
    }

    public function filterLoad(AssetInterface $asset)
    {
        $assetDir = $asset->getSourceDirectory();
        $content = $asset->getContent();
        $newContent = preg_replace_callback(
            self::SEARCH_PATTERN,
            function ($matches) use ($assetDir) {
                $path = $assetDir . '/' . $matches['path'];
                $query = $matches['query'] ?: null;
                $realPath = realpath($path);
                if ($realPath === false) {
                    $newPath = $matches['path'];
                } else {
                    $newPath = str_replace($this->rootPath, '', $realPath);
                    $newPath = str_replace('\\', '/', $newPath);
                    $newPath = ltrim($newPath, '/');
                    $newPath = '/' . $newPath;
                }

                return sprintf(self::URI_PATTERN, $newPath . $query);
            },
            $content
        );

        $asset->setContent($newContent);
    }

    public function filterDump(AssetInterface $asset)
    {
    }
}
