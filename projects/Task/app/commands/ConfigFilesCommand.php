<?php namespace App\Commands;

use Illuminate\Console\Command;

class ConfigFilesCommand extends Command
{
    protected $name = 'app:config-files';
    protected $description = 'Create config files if they have not been created yet.';

    public function fire()
    {
        $sampleFileContent = file_get_contents(base_path('.env.sample.php'));

        $confFiles = [
            base_path('.env.local.php'),
            base_path('.env.testing.php'),
        ];

        foreach ($confFiles as $fileName) {
            if (!file_exists($fileName)) {
                file_put_contents($fileName, $sampleFileContent);
                $this->info("Fill the file {$fileName} with correct data");
            }
        }
    }
}
