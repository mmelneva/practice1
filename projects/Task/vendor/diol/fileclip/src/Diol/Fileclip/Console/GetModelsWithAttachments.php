<?php namespace Diol\Fileclip\Console;

use Diol\Fileclip\Console\Feature\ModelsGetter;
use Illuminate\Console\Command;

/**
 * Class GetModels
 * @package Diol\Fileclip\Console
 */
class GetModelsWithAttachments extends Command
{
    use ModelsGetter;

    protected $name = 'fileclip:get-models-with-attachments';
    protected $description = 'Get models with attachments';

    public function fire()
    {
        $models = $this->getModels();

        foreach ($models as $model) {
            $this->info($model);
        }
    }
}