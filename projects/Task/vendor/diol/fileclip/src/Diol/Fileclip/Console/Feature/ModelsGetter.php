<?php namespace Diol\Fileclip\Console\Feature;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Trait ModelsGetter
 * @package Diol\Fileclip\Console\Feature
 */
trait ModelsGetter
{
    /**
     * Get default models for processing.
     *
     * @return array
     */
    protected function getModels()
    {
        $models = [];

        $modelsRoots = array_filter(
            (array)\Config::get('fileclip::models_root'),
            function ($path) {
                return file_exists($path);
            }
        );

        foreach ($modelsRoots as $modelsRoot) {
            $finder = Finder::create();
            $finder->in($modelsRoot)->name('*.php')->files();

            /** @var SplFileInfo $file */
            foreach ($finder as $file) {
                require_once($file->getPathname());
            }

            $externalClasses = [];
            foreach (get_declared_classes() as $class) {
                $reflectionClass = new \ReflectionClass($class);
                $fileName = $reflectionClass->getFileName();

                if (is_string($fileName)) {
                    $externalClasses[$fileName] = $class;
                }
            }

            foreach ($finder as $file) {
                if ($modelClass = array_get($externalClasses, $file->getPathname())) {
                    $reflectionClass = new \ReflectionClass($modelClass);

                    if ($reflectionClass->IsInstantiable()) {
                        $modelInstance = app($modelClass);

                        if ($modelInstance instanceof \Illuminate\Database\Eloquent\Model
                            && in_array('Diol\Fileclip\Eloquent\Glue', class_uses_recursive($modelClass))
                        ) {
                            $models[] = $modelClass;
                        }
                    }
                }
            }
        }

        sort($models);

        return $models;
    }

}