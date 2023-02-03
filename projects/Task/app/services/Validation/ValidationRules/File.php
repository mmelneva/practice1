<?php namespace App\Services\Validation\ValidationRules;

use Diol\Fileclip\InputFileWrapper\Wrapper\HttpFile;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Factory as ValidatorFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class File
{
    /** @var ValidatorFactory */
    private $validatorFactory;

    public function __construct(ValidatorFactory $validatorFactory)
    {
        $this->validatorFactory = $validatorFactory;
    }

    public function validateLocalOrRemoteFile($attribute, $value, $parameters, Validator $validator)
    {
        if ($value instanceof UploadedFile) {
            $imageValidator = $this->validatorFactory->make(
                [$attribute => $value],
                [$attribute => 'mimes:' . implode(',', $parameters)]
            );

            if ($imageValidator->fails()) {
                $message = $imageValidator->errors()->get($attribute)[0];
                $validator->getMessageBag()->add($attribute, $message);
                return false;
            } else {
                return true;
            }
        } else {
            $urlValidator = $this->validatorFactory->make([$attribute => $value], [$attribute => 'url']);

            $message = preg_replace(
                ['/:attribute/', '/:values/'],
                [trans("validation.attributes.{$attribute}"), implode(', ', $parameters)],
                trans('validation.mimes')
            );

            if ($urlValidator->passes()) {
                $httpFile = new HttpFile($value);
                if (in_array($httpFile->getExtension(), $parameters)) {
                    return true;
                } else {
                    $validator->getMessageBag()->add($attribute, $message);
                    return false;
                }
            } else {
                $validator->getMessageBag()->add($attribute, $message);
                return false;
            }
        }
    }
} 