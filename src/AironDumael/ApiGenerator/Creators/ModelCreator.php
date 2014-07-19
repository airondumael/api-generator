<?php  namespace AironDumael\ApiGenerator\Creators;

use Illuminate\Filesystem\Filesystem;

class ModelCreator extends BaseCreator
{
    protected $directories = [
        '{{MODEL}}',
        '{{MODEL}}/Validators'
    ];

    /**
     * template path => destination path
     * @var array
     */
    protected $files = [
        '../../../template/Model/Validators/ModelCreateValidator.txt'
            => '{{MODEL}}/Validators/{{MODEL}}CreateValidator.php',
        '../../../template/Model/Validators/ModelUpdateValidator.txt'
            => '{{MODEL}}/Validators/{{MODEL}}UpdateValidator.php',
        '../../../template/Model/EloquentModelRepository.txt'
            => '{{MODEL}}/Eloquent{{MODEL}}Repository.php',
        '../../../template/Model/ModelController.txt'
            => '{{MODEL}}/{{MODEL}}Controller.php',
        '../../../template/Model/ModelEloquentModel.txt'
            => '{{MODEL}}/{{MODEL}}EloquentModel.php',
        '../../../template/Model/ModelRepositoryInterface.txt'
            => '{{MODEL}}/{{MODEL}}RepositoryInterface.php',
        '../../../template/Model/ModelTransformer.txt'
            => '{{MODEL}}/{{MODEL}}Transformer.php',
    ];

    public function __construct(Filesystem $f)
    {
        parent::__construct($f);
    }

    public function make($path, $data)
    {
        $path = $this->removeTrailingSlash($path);

        if(!$this->filesystem->exists($path))
        {
            $this->setError("{$data['NAMESPACE']}/{$data['VERSION']} not yet initialize.");

            return false;
        }

        if ($this->filesystem->exists("{$path}/{$data['MODEL']}"))
        {
            $this->setError("{$data['MODEL']} folder already exist.");

            return false;
        }

        foreach ($this->directories as $dir)
        {
            $dirPath = "{$path}/{$dir}";
            $dirPath = $this->makeTemplate($dirPath, $data);
            $this->prepareDirectory($dirPath);
        }

        foreach ($this->files as $templateFile => $destinationFile)
        {
            // get the template
            $templateFile = __DIR__ . "/{$templateFile}";
            $template = $this->makeTemplate(file_get_contents($templateFile), $data);

            $destinationFile = $this->makeTemplate($destinationFile, $data);

            //create the template
            $this->create($destinationFile, $path, $template);
        }

        return true;
    }
} 