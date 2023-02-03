<?php
namespace Diol\Fileclip\support;

trait FilesystemTestTools
{
    public static function getTestStoragePath()
    {
        return __DIR__ . '/../../../test_storage';
    }

    public static function getTestPublicPath()
    {
        return __DIR__ . '/../../../test_public';
    }

    public static function getTestFixturesPath()
    {
        return __DIR__ . '/../../../fixtures';
    }

    public function recreateTestPublic()
    {
        $this->removeTestPublic();
        mkdir($this->getTestPublicPath());
    }

    public function removeTestPublic()
    {
        $testDir = $this->getTestPublicPath();
        if (file_exists(($testDir))) {
            $this->deleteDirectoryRecursively($testDir);
        }
    }

    public function recreateTestStorage()
    {
        $this->removeTestStorage();
        mkdir($this->getTestStoragePath());
    }

    public function removeTestStorage()
    {
        $testDir = $this->getTestStoragePath();
        if (file_exists(($testDir))) {
            $this->deleteDirectoryRecursively($testDir);
        }
    }

    protected function deleteDirectoryRecursively($dirPath)
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dirPath, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($iterator as $path) {
            $path->isFile() ? unlink($path->getPathname()) : rmdir($path->getPathname());
        }
        rmdir($dirPath);
    }
}
