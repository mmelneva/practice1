<?php namespace Diol\LaravelAssets;

use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Asset\HttpAsset;

/**
 * Class AssetManager
 * @package Diol\LaravelAssets
 */
class AssetManager
{
    /**
     * @var array
     */
    private $groups;
    /**
     * @var array
     */
    private $filters;

    public function __construct(array $groups, array $filterAliases)
    {
        $this->groups = $groups;
        $this->filters = $this->createFilters($filterAliases);
    }

    /**
     * Create filters - filter objects.
     *
     * @param array $filterAliases
     * @return array
     */
    private function createFilters(array $filterAliases)
    {
        $filters = [];
        foreach ($filterAliases as $filterKey => $filter) {
            if (is_object($filter)) {
                $filters[$filterKey] = $filter;
            } else {
                $filters[$filterKey] = app($filter);
            }
        }

        return $filters;
    }

    /**
     * Get list of assets for group.
     *
     * @param $groupName
     * @return mixed
     */
    public function getAssetFiles($groupName)
    {
        return $this->groups[$groupName]['assets'];
    }

    /**
     * Get output file for group.
     *
     * @param $groupName
     * @return mixed
     */
    public function getOutputAssetFile($groupName)
    {
        return $this->groups[$groupName]['output'];
    }


    /**
     * Check if you need async mode
     *
     * @param $groupName
     * @return bool
     */
    public function checkAsyncMode($groupName)
    {
        try {
            if($this->groups[$groupName]['async'] === true)
                return true;
            return false;
        } catch (\Exception $exception) {
            return false;
        }
    }


    /**
     * Compile group by name. It will save the file on filesystem.
     *
     * @param $groupName
     */
    public function compileGroup($groupName)
    {
        $assetObjectList = $this->createAssetObjects($groupName);
        $assetFilterObjects = $this->getFilterObjects($groupName);

        $collection = new AssetCollection($assetObjectList, $assetFilterObjects);
        $outputFile = public_path($this->getOutputAssetFile($groupName));

        file_put_contents($outputFile, $collection->dump());
    }

    /**
     * Return list of groups.
     *
     * @return array
     */
    public function getGroupNames()
    {
        return array_keys($this->groups);
    }

    /**
     * Create asset objects for group.
     *
     * @param $groupName
     * @return \Assetic\Asset\AssetInterface[]
     */
    private function createAssetObjects($groupName)
    {
        $assetObjectList = [];
        $assetList = $this->getAssetFiles($groupName);

        foreach ($assetList as $asset) {
            if (starts_with($asset, ['http://', 'https://'])) {
                $assetObject = new HttpAsset($asset);
            } else {
                $assetObject = new FileAsset(public_path($asset));
            }

            $assetObjectList[] = $assetObject;
        }

        return $assetObjectList;
    }

    /**
     * Get filter objects for group.
     *
     * @param $groupName
     * @return array
     */
    private function getFilterObjects($groupName)
    {
        $groupFilters = [];
        $filterNames = $this->groups[$groupName]['filters'];

        foreach ($filterNames as $filterKey) {
            $groupFilters[] = $this->filters[$filterKey];
        }

        return $groupFilters;
    }
}
