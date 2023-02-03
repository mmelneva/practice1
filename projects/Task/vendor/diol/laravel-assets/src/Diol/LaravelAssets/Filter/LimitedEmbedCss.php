<?php namespace Diol\LaravelAssets\Filter;

use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;

/**
 * Class LimitedEmbedCss
 * Filter to embed url into css but not all. There are limit size for file in url.
 *
 * @package Diol\LaravelAssets\Filter
 */
class LimitedEmbedCss implements FilterInterface
{
    const SEARCH_PATTERN = "%url\\(['\" ]*((?!data:|//)[^'\"#\?: ]+)['\" ]*\\)%U";
    const URI_PATTERN = "url(data:%s;base64,%s)";

    /**
     * @var int
     */
    private $sizeLimit;

    /**
     * @param int $sizeLimit
     */
    public function __construct($sizeLimit = 5000)
    {
        $this->sizeLimit = $sizeLimit;
    }

    public function filterLoad(AssetInterface $asset)
    {
        $assetDir = $asset->getSourceDirectory();
        $content = $asset->getContent();

        $newContent = preg_replace_callback(
            self::SEARCH_PATTERN,
            function ($matches) use ($assetDir) {
                return $this->getNewUrl($assetDir, $matches);
            },
            $content
        );

        $asset->setContent($newContent);
    }

    public function filterDump(AssetInterface $asset)
    {
    }

    /**
     * Get new url in asset dir - relative or base64.
     *
     * @param $assetDir
     * @param $matches
     * @return string
     */
    private function getNewUrl($assetDir, $matches)
    {
        $file = $assetDir . '/'. $matches[1];
        if (!is_file($file)) {
            $newUrl = $matches[0];
        } else {
            $size = filesize($file);
            if ($size <= $this->sizeLimit) {
                $newUrl = $this->getEmbedUrl($file);
            } else {
                $newUrl = $matches[0];
            }
        }

        return $newUrl;
    }

    /**
     * Get embed url for file.
     *
     * @param $file
     * @return string
     */
    private function getEmbedUrl($file)
    {
        return sprintf(self::URI_PATTERN, $this->mimeType($file), $this->base64($file));
    }

    /**
     * Get mime type for file.
     *
     * @param $file
     * @return string
     */
    private function mimeType($file)
    {
        if (function_exists('mime_content_type')) {
            return mime_content_type($file);
        }

        if ($info = @getimagesize($file)) {
            return($info['mime']);
        }

        return 'application/octet-stream';
    }

    /**
     * Get base64 for file.
     *
     * @param $file
     * @return string
     */
    private function base64($file)
    {
        return base64_encode(file_get_contents($file));
    }
}
