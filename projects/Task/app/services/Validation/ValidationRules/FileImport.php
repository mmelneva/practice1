<?php namespace App\Services\Validation\ValidationRules;

use Diol\Fileclip\InputFileWrapper\Wrapper\LocalSystemFile;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Factory as ValidatorFactory;

class FileImport
{
    /** @var ValidatorFactory */
    private $validatorFactory;

    public function __construct(ValidatorFactory $validatorFactory)
    {
        $this->validatorFactory = $validatorFactory;
    }

    public function validateImportedFile($attribute, $value, $parameters, Validator $validator)
    {
        $file = new LocalSystemFile($value);
        if (in_array($file->getExtension(), $parameters)) {
            return true;
        } else {
            $message = preg_replace(
                ['/:attribute/', '/:values/'],
                [trans("validation.attributes.{$attribute}"), implode(', ', $parameters)],
                trans('validation.mimes')
            );
            $validator->getMessageBag()->add($attribute, $message);

            return false;
        }
    }
}