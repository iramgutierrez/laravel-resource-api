<?php

namespace Iramgutierrez\API;

use Illuminate\Console\Command;

use Iramgutierrez\API\Generators\EntityGenerator as Entity;
use Iramgutierrez\API\Generators\RepositoryGenerator as Repository;
use Iramgutierrez\API\Generators\ValidatorGenerator as Validator;
use Iramgutierrez\API\Generators\ManagerGenerator as Manager;
use Iramgutierrez\API\Generators\ControllerGenerator as Controller;
use Iramgutierrez\API\Generators\RouteGenerator as Route;
use Iramgutierrez\API\Generators\MigrationGenerator as Migration;

class ResourceAPI extends Command
{
    protected $base;

    protected $table;

    protected $prefix;

    protected $documentation;

    protected $entity;

    protected $repository;

    protected $validator;

    protected $manager;

    protected $controller;

    protected $route;

    protected $migration;

    protected $path;

    protected $layers = [
        'entity',
        'repository',
        'validator',
        'manager',
        'controller'
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-resource-api
                            {entity : The entity singular name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new API Resource';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Entity $Entity, Repository $Repository, Validator $Validator, Manager $Manager, Controller $Controller, Route $Route , Migration $Migration)
    {
        parent::__construct();

        $this->entity = $Entity;

        $this->repository = $Repository;

        $this->validator = $Validator;

        $this->manager = $Manager;

        $this->controller = $Controller;

        $this->route = $Route;

        $this->migration = $Migration;

        $this->path = \Config::get('resource_api.path' , 'API');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->base = ucfirst(camel_case(str_singular($this->argument('entity'))));

        $this->table = $this->ask('Table name' , snake_case(str_plural($this->base)));

        $this->prefix = $this->ask('Prefix route' , false);

        $this->documentation = $this->confirm('Generate documentation? (Require apidocjs)' , 'yes');

        $createLayers = [];

        foreach($this->layers as $layer)
        {

            $createLayer = $this->checkIfCreate($layer);

            if(!$createLayer)
            {
                $this->error('Aborted!');

                exit();
            }

            $createLayers[$layer] = true;

        }

        $this->info("The following files will be created:");

        foreach($createLayers as $layer => $create)
        {
            $file = $this->$layer->getFilename();

            $this->info($file);
        }

        if($this->confirm('Continue?' , 'yes'))
        {


            foreach($createLayers as $layer => $create)
            {
                $generate = $this->$layer->generate();

                $this->info($generate);
            }

            if($this->confirm('Generate migration?' , 'yes'))
            {

                $createMigration = $this->migration->generate(snake_case(str_plural($this->base)) , $this->table);

                if($createMigration['success'])
                {
                    $this->info($createMigration['message']);

                    if($this->confirm('Run migration?' , 'yes'))
                    {

                        try
                        {

                            $this->call('migrate', [
                                '--path' => 'database/migrations/'.snake_case(str_plural($this->base))
                            ]);

                        }catch (\Exception $e)
                        {
                            $this->error($e->getMessage());

                        }

                    }

                }
                else
                {

                    $this->error($createMigration['error']);
                }
            }

            if($this->confirm('Add routes resource?' , 'yes'))
            {

                $path = snake_case(str_plural($this->base));

                $middlewares = $this->ask('Middlewares or middleware groups (comma separated)' , false);

                $mdws = [];

                if($middlewares)
                {
                    $mdws = explode(',' , $middlewares);

                }

                $this->route->generate($this->base , $path , $this->prefix , $mdws);

            }

            if($this->documentation)
            {
                $this->info('apidoc -i '.app_path().'/Http/Controllers/'.$this->path.'/ -f "'.$this->base.'Controller.php" -o '.public_path().'/docs/'.snake_case(str_plural($this->base)));

                exec('apidoc -i '.app_path().'/Http/Controllers/'.$this->path.'/ -f "'.$this->base.'Controller.php" -o '.public_path().'/docs/'.snake_case(str_plural($this->base)));
            }

        }
        else
        {
            $this->error('Aborted!');

            exit();
        }

        $this->info('Finish!');

        exit();

    }

    public function checkIfCreate($layer)
    {

        $this->$layer->setEntity($this->base);

        if($layer == 'entity' || $layer == 'migration')
        {
            $this->$layer->setTable($this->table);

            $this->$layer->setTable($this->table);
        }

        if($layer == 'controller')
        {
            $this->$layer->setPrefix($this->prefix);

            $this->$layer->setDocumentation($this->documentation);
        }

        $filename = $this->$layer->getFilename();

        $exists = $this->$layer->checkExists();

        if($exists)
        {
            return $this->confirm('File '.$filename.' exists. Overwrite? [y|N]');
        }

        return true;

    }

}
