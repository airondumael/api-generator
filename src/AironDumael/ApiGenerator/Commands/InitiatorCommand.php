<?php  namespace AironDumael\ApiGenerator\Commands;

use Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\InputArgument;
use AironDumael\ApiGenerator\Creators\Initiator;
use Config;

class InitiatorCommand extends BaseCommand {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'api-generator:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize API namespace directory';

    protected $creator;
    private $path;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Initiator $p)
    {
        parent::__construct();

        $this->creator = $p;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $path = $this->getPath();
        $data = $this->getData();

        if($this->creator->make($path, $data))
        {
            return $this->info("Preparation has been completed in {$this->path}");
        }

        $this->error("Could not make preparation in {$this->path}");
        $this->error($this->creator->getError());
    }

    protected function getPath()
    {
        return $this->path = $this->ucwordsOption('basedirectory') . '/' . $this->option('namespace') . '/' . $this->option('ver');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            [
                'namespace',
                null,
                InputOption::VALUE_OPTIONAL,
                'namespace',
                Config::get('api-generator::config.namespace'),
            ],
            [
                'ver',
                null,
                InputOption::VALUE_OPTIONAL,
                'ver',
                Config::get('api-generator::config.ver'),
            ],
            [
                'basedirectory',
                null,
                InputOption::VALUE_OPTIONAL,
                'Base directory of modules',
                Config::get('api-generator::config.base_directory'),
            ],
            [
                'model',
                null,
                InputOption::VALUE_OPTIONAL,
                'Model Name',
                null
            ]
        ];
    }

    protected function getData()
    {
        return [
            'BASE_DIRECTORY' => $this->ucwordsOption('basedirectory'),
            'NAMESPACE' => $this->option('namespace'),
            'MODEL' => $this->option('model'),
            'VERSION' => $this->option('ver')
        ];
    }

} 