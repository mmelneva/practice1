<?php namespace Diol\Fileclip\Console;

use Diol\Fileclip\Console\Feature\ModelsGetter;
use Diol\Fileclip\Eloquent\Attachment;
use Diol\Fileclip\Eloquent\Glue;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class GetMissingOriginalsCommand
 * @package App\Command
 */
class GetMissingOriginalsCommand extends Command
{
    use ModelsGetter;

    protected $name = 'fileclip:get-missing-originals';
    protected $description = 'Get missing original files for models';

    protected function getArguments()
    {
        $mode = InputArgument::IS_ARRAY;

        if (count($this->getModels()) === 0) {
            $mode |= InputArgument::REQUIRED;
        }

        return [
            ['model', $mode, 'List of model classes']
        ];
    }

    public function fire()
    {
        $storagePaths = [];
        $models = $this->argument('model') ?: $this->getModels();

        foreach ($models as $modelClass) {
            if (!class_exists($modelClass, true)) {
                $this->error("Class {$modelClass} does not exist!");
                continue;
            }

            $modelInstance = \App::make($modelClass);

            if (false === $modelInstance instanceof \Illuminate\Database\Eloquent\Model) {
                $this->error("Class {$modelClass} must be instance of Illuminate\Database\Eloquent\Model");
                continue;
            }

            $modelInstance::boot();

            if (!in_array('Diol\Fileclip\Eloquent\Glue', class_uses_recursive($modelClass))) {
                $this->error("Class {$modelClass} must use Diol\Fileclip\Eloquent\Glue trait");
                continue;
            }

            $attachmentFieldList = $modelInstance->getAttachmentFields();
            $query = $modelInstance->newQuery();

            $query->chunk(100, function ($chunk) use ($attachmentFieldList, &$storagePaths) {
                /** @var Glue|Model $model */
                foreach ($chunk as $model) {
                    foreach ($attachmentFieldList as $field) {
                        if (null !== $model->getAttribute($field)) {
                            /** @var Attachment $attachment */
                            $attachment = $model->getAttachment($field);

                            if (null !== $attachment) {
                                $uploader = $attachment->getUploader();
                                $storagePath = $uploader->getAbsoluteStoragePath($model->getAttribute($field));

                                if (!file_exists($storagePath)) {
                                    $storagePaths[] = $storagePath;
                                }
                            }
                        }
                    }
                }
            });
        }

        foreach ($storagePaths as $path) {
            $this->info($path);
        }
    }
}
