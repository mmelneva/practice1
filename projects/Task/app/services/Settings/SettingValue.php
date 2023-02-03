<?php
namespace App\Services\Settings;

use App\Services\Settings\Exception\IllegalDefaultValue;
/**
 * Class SettingValue
 * @package App\Services\Settings
 */
class SettingValue
{
    const TYPE_TEXT = 'text';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_TEXTAREA_TINYMCE = 'textarea_tinymce';
    const TYPE_REDIRECTS = 'redirects';
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $type;

    /**
     * @var null
     */
    private $defaultValue;

    /**
     * @var array
     */
    private $rules = [];

    /**
     * @param $key
     * @param $name
     * @param string $defaultValue
     * @param string $description
     * @param string $type
     */
    public function __construct($key, $name, $defaultValue = '', $description = '', $type = self::TYPE_TEXT, array $rules = [])
    {
        $this->key = $key;
        $this->name = $name;
        $this->defaultValue = $defaultValue;
        $this->description = $description;
        $this->type = $type;
        $this->rules = $rules;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return null
     */
    public function getDefaultValue()
    {
        if (\Validator::make(['v' => $this->defaultValue], ['v' => $this->getRules()])->fails()) {
            throw new IllegalDefaultValue(
                "Illegal default value '{$this->defaultValue}' for setting with '{$this->key}' key."
            );
        }

        return $this->defaultValue;
    }

    /**
     * @return string
     */
    public function getRules()
    {
        return $this->rules;
    }

}
