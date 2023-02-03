<?php namespace App\Services\UrlBuilder;

/**
 * Class UrlBuilder
 * @package App\Services\UrlBuilder
 */
class UrlBuilder
{
    private $conditionsDataList = [];

    public function add($condition, $builder)
    {
        $this->conditionsDataList[] = ['condition' => $condition, 'builder' => $builder];
    }

    public function getUrl($object)
    {
        foreach ($this->conditionsDataList as $conditionData) {
            if ($conditionData['condition'] ($object)) {
                return $conditionData['builder'] ($object);
            }
        }
        throw new \LogicException('Incorrect url data');
    }
}