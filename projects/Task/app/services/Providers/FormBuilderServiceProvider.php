<?php namespace App\Services\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\ViewErrorBag;
use Form;
use Html;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

class FormBuilderServiceProvider extends ServiceProvider
{
    /**
     * Ext form data cache.
     * @var array
     */
    private $extFormData = [];

    /**
     * Field description cache.
     * @var null
     */
    private $fieldDescriptions = null;

    /**
     * @inheritDoc
     */
    public function register()
    {
    }

    public function boot()
    {
        $this->initTbFormWithErrors();
        $this->initTbModelWithErrors();
        $this->initTbResultFormOpen();
        $this->initTbFields();
        $this->initTbFormGroup();
        $this->initFieldBlocks();
    }

    /**
     * Get field description for field.
     *
     * @param $field
     * @return mixed
     */
    private function getFieldDescription($field)
    {
        if (is_null($this->fieldDescriptions)) {
            try {
                $ymlParser = new Parser();
                $fieldDescriptions = $ymlParser->parse(\SettingGetter::get('admin.field_descriptions'));
            } catch (ParseException $e) {
                $fieldDescriptions = [];
            }

            $this->fieldDescriptions = (array) $fieldDescriptions;
        }

        return array_get($this->fieldDescriptions, $field);
    }

    /**
     * Get block with field description for field.
     *
     * @param $field
     * @return string
     */
    private function getFieldDescriptionBlock($field)
    {
        $fieldDescription = $this->getFieldDescription($field);
        if (!is_null($fieldDescription)) {
            $hintBlock = "<div class=\"field-hint-block\">{$fieldDescription}</div>";
        } else {
            $hintBlock = '';
        }

        return $hintBlock;
    }

    private function initTbFormWithErrors()
    {
        Form::macro(
            'tbFormWithErrorsOpen',
            function (ViewErrorBag $errors, $options) {
                $this->extFormData['modelFormErrors'] = $errors;
                $ret = Form::open($options);
                if (!is_null($errors) && $errors->has()) {
                    $ret .= '<div class="alert alert-danger">' . trans('alerts.validation_error') . '</div>';
                }

                return $ret;
            }
        );
    }

    private function initTbModelWithErrors()
    {
        Form::macro(
            'tbModelWithErrors',
            function ($model, ViewErrorBag $errors, $options) {
                $this->extFormData['modelFormErrors'] = $errors;
                $ret = Form::model($model, $options);
                if (!is_null($errors) && $errors->has()) {
                    $ret .= '<div class="alert alert-danger">' . trans('alerts.validation_error') . '</div>';
                }

                return $ret;
            }
        );
    }

    private function initTbResultFormOpen()
    {
        Form::macro(
            'tbRestfulFormOpen',
            function ($model, ViewErrorBag $errors, $controller, $options = []) {
                $options['files'] = true;
                if (isset($model['id']) && !empty($model['id'])) {
                    $options['url'] = action($controller . '@putUpdate', [$model['id']]);
                    $options['method'] = 'put';
                } else {
                    $options['url'] = action($controller . '@postStore');
                    $options['method'] = 'post';
                }

                return Form::tbModelWithErrors($model, $errors, $options);
            }
        );
    }

    /**
     * Add css class to options.
     *
     * @param array $options
     * @param $className
     * @return array
     */
    private function addClass(array $options, $className)
    {
        if (isset($options['class'])) {
            $options['class'] = $className . ' ' . $options['class'];
        } else {
            $options['class'] = $className;
        }

        return $options;
    }

    private function initTbFields()
    {
        Form::macro(
            'tbLabel',
            function ($name, $value = null, $options = []) {
                $hintBlock = $this->getFieldDescriptionBlock($value);
                return Form::label($name, $value, $this->addClass($options, 'control-label')) . $hintBlock;
            }
        );

        Form::macro(
            'tbLegend',
            function ($name, $options = []) {
                $hintBlock = $this->getFieldDescriptionBlock($name);
                if (!empty($hintBlock)) {
                    $options = $this->addClass($options, 'legend-hint');
                }
                $options = Html::attributes($options);

                return '<legend ' . $options . '>' . $name . '</legend>' . $hintBlock;
            }
        );

        Form::macro(
            'tbText',
            function ($name, $value = null, $options = []) {
                return Form::text($name, $value, $this->addClass($options, 'form-control'));
            }
        );

        Form::macro(
            'tbPassword',
            function ($name, $options = []) {
                return Form::password($name, $this->addClass($options, 'form-control'));
            }
        );

        Form::macro(
            'tbSelect',
            function ($name, $list = [], $selected = null, $options = []) {
                return Form::select($name, $list, $selected, $this->addClass($options, 'form-control input-sm half'));
            }
        );

        Form::macro(
            'tbSelect2',
            function ($name, $list = [], $selected = null, $options = []) {
                $options['data-with-search'] = true;
                return Form::select($name, $list, $selected, $this->addClass($options, 'form-control input-sm half'));
            }
        );

        Form::macro(
            'tbTextarea',
            function ($name, $value = null, $options = array()) {
                return Form::textarea($name, $value, $this->addClass($options, 'form-control'));
            }
        );

        Form::macro(
            'tbTinymceTextarea',
            function ($name, $value = null, $options = array()) {
                $options['data-tinymce'] = '';
                if (!isset($options['rows'])) {
                    $options['rows'] = 15;
                }

                return Form::textarea($name, $value, $this->addClass($options, 'form-control'));
            }
        );

        Form::macro(
            'tbStateCheckbox',
            function ($name, $fieldName, $checked = null) {
                $hintBlock = $this->getFieldDescriptionBlock($fieldName);

                return '<input type="hidden" name="' . $name . '" value="0" />' .
                '<label class="checkbox-inline">' .
                Form::checkbox($name, 1, $checked) .
                '<span class="bold">' . $fieldName . '</span>' .
                '</label>'.
                $hintBlock;
            }
        );
    }

    private function initTbFormGroup()
    {
        Form::macro(
            'tbFormGroupOpen',
            function ($name = null) {
                $this->extFormData['formGroupName'] = $name;
                $classes = ['form-group'];
                if (isset($this->extFormData['modelFormErrors'])
                    && $this->extFormData['modelFormErrors']->has($this->extFormData['formGroupName'])
                ) {
                    $classes[] = 'has-error';
                }

                return '<div class="' . implode(' ', $classes) . '">';
            }
        );

        Form::macro(
            'tbFormGroupClose',
            function () {
                $ret = '';
                $ret .= Form::tbFormFieldError($this->extFormData['formGroupName']);
                $ret .= '</div>';

                return $ret;
            }
        );

        Form::macro(
            'tbFormFieldError',
            function ($key) {
                $ret = '';
                if (isset($this->extFormData['modelFormErrors']) && $this->extFormData['modelFormErrors']->has($key)) {
                    $ret .= '<div class="validation-errors">'
                        . implode(
                            '<br />',
                            $this->extFormData['modelFormErrors']->get($key)
                        )
                        . '</div>';
                }

                return $ret;
            }
        );
    }


    private function getLabelName($name, $labelName = null)
    {
        if (is_null($labelName)) {
            $labelName = trans("validation.attributes.{$name}");
        }

        return $labelName;
    }

    private function initFieldBlocks()
    {
        Form::macro(
            'tbTextBlock',
            function ($name, $labelName = null, $value = null, $options = []) {
                return
                    Form::tbFormGroupOpen($name) .
                    Form::tbLabel($name, $this->getLabelName($name, $labelName)) .
                    Form::tbText($name, $value, $options) .
                    Form::tbFormGroupClose();
            }
        );

        Form::macro(
            'tbPasswordBlock',
            function ($name, $labelName = null) {
                return
                    Form::tbFormGroupOpen($name) .
                    Form::tbLabel($name, $this->getLabelName($name, $labelName)) .
                    Form::tbPassword($name) .
                    Form::tbFormGroupClose();
            }
        );

        Form::macro(
            'tbCheckboxBlock',
            function ($name, $labelName = null) {
                return
                    Form::tbFormGroupOpen($name) .
                    Form::tbStateCheckbox($name, $this->getLabelName($name, $labelName)) .
                    Form::tbFormGroupClose();
            }
        );

        Form::macro(
            'tbTextareaBlock',
            function ($name, $labelName = null, $value = null, $options = array()) {
                return
                    Form::tbFormGroupOpen($name) .
                    Form::tbLabel($name, $this->getLabelName($name, $labelName)) .
                    Form::tbTextarea($name, $value, $options) .
                    Form::tbFormGroupClose();
            }
        );

        Form::macro(
            'tbTinymceTextareaBlock',
            function ($name, $labelName = null, $value = null, $options = array()) {
                return
                    Form::tbFormGroupOpen($name) .
                    Form::tbLabel($name, $this->getLabelName($name, $labelName)) .
                    Form::tbTinymceTextarea($name, $value, $options) .
                    Form::tbFormGroupClose();
            }
        );

        Form::macro(
            'tbSelectBlock',
            function ($name, array $variants = [], $labelName = null, $value = null, $options = []) {
                return
                    Form::tbFormGroupOpen($name) .
                    Form::tbLabel($name, $this->getLabelName($name, $labelName)) .
                    Form::tbSelect($name, $variants, $value, $options) .
                    Form::tbFormGroupClose();
            }
        );
    }
}
