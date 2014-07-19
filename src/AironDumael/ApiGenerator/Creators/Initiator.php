<?php  namespace AironDumael\ApiGenerator\Creators;

use Illuminate\Filesystem\Filesystem;

class Initiator extends BaseCreator
{
    protected $directories = [
        '',
        'API',
        'Repository',
        'Validator'
    ];

    /**
     * template path => destination path
     * @var array
     */
    protected $files = [
        '../../../template/API/AbstractTransformer.txt' => 'API/AbstractTransformer.php',
        '../../../template/API/ApiController.txt' => 'API/ApiController.php',
        '../../../template/Repository/AbstractEloquentRepository.txt' => 'Repository/AbstractEloquentRepository.php',
        '../../../template/Repository/RepositoryInterface.txt' => 'Repository/RepositoryInterface.php',
        '../../../template/Validator/ValidatorInterface.txt' => 'Validator/ValidatorInterface.php',
        '../../../template/Validator/AbstractValidator.txt' => 'Validator/AbstractValidator.php',
        '../../../template/ServiceProvider.txt' => '{{NAMESPACE}}{{VERSION}}ServiceProvider.php'
    ];

    public function __construct(Filesystem $f)
    {
        parent::__construct($f);
    }

    public function make($path, $data)
    {
        $path = $this->removeTrailingSlash($path);

        // check in the current path if dir Core exists or not
        if ($this->filesystem->exists("{$path}"))
        {
            $module = basename($path);
            $this->setError("Already initiated.");

            return false;
        }

        foreach ($this->directories as $dir)
        {
            $dirPath = "{$path}/{$dir}";
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