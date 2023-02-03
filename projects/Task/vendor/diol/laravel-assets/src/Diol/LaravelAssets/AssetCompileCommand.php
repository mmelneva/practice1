<?php namespace Diol\LaravelAssets;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class AssetCompileCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'asset:compile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate asset output to disk.';

    /**
     * @var AssetManager
     */
    private $assetManager;
    /**
     * @var array
     */
    private $mergeEnvironments;

    /**
     * Create command handler.
     *
     * @param array $mergeEnvironments
     * @param AssetManager $assetManager
     */
    public function __construct(AssetManager $assetManager, array $mergeEnvironments)
    {
        parent::__construct();

        $this->assetManager = $assetManager;
        $this->mergeEnvironments = $mergeEnvironments;
    }

    /**
     * Execute the command.
     */
    public function fire()
    {
        $force = $this->option('force');
        $env = \App::environment();
        if ($force == 1 || in_array($env, $this->mergeEnvironments)) {
            foreach ($this->assetManager->getGroupNames() as $groupName) {
                $this->assetManager->compileGroup($groupName);
                $this->info(
                    "Group {$groupName}: " . $this->assetManager->getOutputAssetFile($groupName) . " is compiled"
                );
            }
        }
    }

    protected function getOptions()
    {
        return array(
            array('force', null, InputOption::VALUE_OPTIONAL, 'Force compilation')
        );
    }
}
