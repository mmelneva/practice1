<?php namespace Diol\Fileclip\Console;

use Diol\Fileclip\Console\Feature\ModelsGetter;
use Diol\Fileclip\Eloquent\Glue;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\NullOutput;

/**
 * Class UpdateAttachmentsCommand
 * @package Diol\Fileclip\Console
 */
class UpdateAttachmentsCommand extends Command
{
    use ModelsGetter;

    protected $name = 'fileclip:update-attachments';
    protected $description = 'Update attachments for models.';

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
        $oldIgnoreGdJpegWarning = ini_get('gd.jpeg_ignore_warning');

        // Ignore warnings (but not errors) created by libjpeg(-turbo).
        ini_set('gd.jpeg_ignore_warning', 1);

        $models = $this->argument('model') ?: $this->getModels();

        foreach ($models as $modelClass) {
            $this->output = new ConsoleOutput;

            if (!class_exists($modelClass, true)) {
                $this->error("Class {$modelClass} does not exist!");
                continue;
            }

            $modelInstance = app($modelClass);

            if (false === $modelInstance instanceof \Illuminate\Database\Eloquent\Model) {
                $this->error("Class {$modelClass} must be instance of Illuminate\Database\Eloquent\Model");
                continue;
            }

            $modelInstance::boot();

            if (!in_array('Diol\Fileclip\Eloquent\Glue', class_uses_recursive($modelClass))) {
                $this->error("Class {$modelClass} must use Diol\Fileclip\Eloquent\Glue trait");
                continue;
            }


            if (!$this->option('verbose')) {
                $this->output = new NullOutput;
            }

            $this->info("Updating attachments for {$modelClass}");

            $attachmentFieldList = $modelInstance->getAttachmentFields();
            $query = $modelInstance->newQuery();
            $count = $modelInstance->count();

            $progress = new ProgressBar($this->getOutput(), $count);
            $progress->start();

            $query->chunk(100, function ($chunk) use ($attachmentFieldList, $progress) {
                /** @var Glue $model */
                foreach ($chunk as $model) {
                    foreach ($attachmentFieldList as $attachmentField) {
                        $model->getAttachment($attachmentField)->updateVersions();
                    }

                    $progress->advance();
                }
            });

            $progress->finish();
            $this->info(PHP_EOL);
        }

        ini_set('gd.jpeg_ignore_warning', $oldIgnoreGdJpegWarning);
    }
}
