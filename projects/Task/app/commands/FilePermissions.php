<?php namespace App\Commands;

use Illuminate\Console\Command;

class FilePermissions extends Command
{
    protected $name = 'app:file-permissions';
    protected $description = 'Set appropriate file permissions to the files and directories.';

    public function fire()
    {
        $commands = [
            'chmod a+x ' . base_path('artisan'),
            'chmod a+w -R ' . storage_path(),
            'chmod a+w -R ' . public_path('cc_uploads'),
            'chmod a+w -R ' . public_path('uploads'),
            'chmod a+w ' . public_path('request-filter.phar'),
            'chmod a+w ' . public_path('sitemap.xml'),
        ];

        foreach ($commands as $cmd) {
            $this->info($cmd);
            $result = shell_exec($cmd);
            if (!empty($result)) {
                $this->error($result);
            }
        }
    }
}
