<?php  namespace AironDumael\ApiGenerator;

use AironDumael\ApiGenerator\Commands\ModelCommand;
use AironDumael\ApiGenerator\Creators\ModelCreator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Facades\Config;
use AironDumael\ApiGenerator\Creators\Initiator;
use AironDumael\ApiGenerator\Commands\InitiatorCommand;

class ApiGeneratorServiceProvider extends ServiceProvider {
    protected $defer = true;

    public function register()
    {
        $this->package('airondumael/api-generator');

        $this->registerCommands();
    }

    protected function registerCommands()
    {
        $this->registerInitCommand();
    }

    protected function registerInitCommand()
    {
        $this->app['api-generator.init'] = $this->app->share(function($app)
        {
            $initiator = new Initiator($app['files']);
            return new InitiatorCommand($initiator);
        });

        $this->app['api-generator.entity'] = $this->app->share(function($app)
        {
            $initiator = new ModelCreator($app['files']);
            return new ModelCommand($initiator);
        });

        $this->commands('api-generator.init');
        $this->commands('api-generator.entity');
    }
} 