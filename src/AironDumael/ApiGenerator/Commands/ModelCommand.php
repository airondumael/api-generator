<?php  namespace AironDumael\ApiGenerator\Commands;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\InputArgument;
use AironDumael\ApiGenerator\Creators\ModelCreator;
use Config;

class ModelCommand extends BaseCommand {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'api-generator:entity';

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
    public function __construct(ModelCreator $p)
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
            return $this->info("Model {$data['MODEL']} has been completed in {$this->path}");
        }

        $this->error("Could not create {$data['MODEL']} in {$this->path}");
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
                InputOption::VALUE_REQUIRED,
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
            'MODEL' => ucwords($this->option('model')),
            'MODEL_PLURAL' => Str::plural($this->option('model')),
            'VERSION' => $this->option('ver')
        ];
    }

} 