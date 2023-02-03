<?php namespace Diol\LaravelAssets;

/**
 * Class Asset
 * Class helper to print out the assets.
 *
 * @package Diol\LaravelAssets
 */
class Asset
{
    /**
     * @var AssetManager
     */
    private $assetManager;
    /**
     * @var array
     */
    private $mergeEnvironments;

    /**
     * @param AssetManager $assetManager
     * @param array $mergeEnvironments - list of environments to merge.
     */
    public function __construct(AssetManager $assetManager, array $mergeEnvironments)
    {
        $this->assetManager = $assetManager;
        $this->mergeEnvironments = $mergeEnvironments;
    }

    /**
     * Include JS tags.
     *
     * @param string $groupName - group name.
     * @param null $template - template for link tag. Need to put {{file}} inside.
     * @return string
     */
    public function includeJS($groupName, $template = null)
    {
        if (is_null($template)) {
            if($this->assetManager->checkAsyncMode($groupName)){
                $template = '<script type="text/javascript" src="{{file}}" defer></script>';
            }
            else{
                $template = '<script type="text/javascript" src="{{file}}" ></script>';
            }
        }
        return $this->includeAsset($groupName, $template);
    }

    /**
     * Include CSS tags.
     *
     * @param string $groupName - group name.
     * @param null $template - template for link tag. Need to put {{file}} inside.
     * @return string
     */
    public function includeCSS($groupName, $template = null)
    {
        if (is_null($template)) {
            if($this->assetManager->checkAsyncMode($groupName)){
                $template = '<link rel="preload" href="{{file}}" as="style" onload="this.onload=null;this.rel=\'stylesheet\'" /> <noscript><link rel="stylesheet" href="{{file}}"></noscript>';
            }
            else{
                $template = '<link rel="stylesheet" href="{{file}}" />';
            }
        }
        return $this->includeAsset($groupName, $template);
    }

    /**
     * Include asset.
     *
     * @param $groupName
     * @param $template
     * @return string
     */
    private function includeAsset($groupName, $template)
    {
        $env = \App::environment();
        $merge = in_array($env, $this->mergeEnvironments);

        if ($merge) {
            $files = [$this->assetManager->getOutputAssetFile($groupName)];
        } else {
            $files = $this->assetManager->getAssetFiles($groupName);
        }

        $taggedFiles = [];
        foreach ($files as $f) {
            $fileStr = asset($f);
            $filePath = public_path($f);
            if (is_file($filePath)) {
                $fileStr .= '?v=' . filemtime($filePath);
            }
            $taggedFiles[] = str_replace('{{file}}', $fileStr, $template);
        }

        return implode("\n", $taggedFiles);
    }
}
