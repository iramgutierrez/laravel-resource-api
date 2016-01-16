<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use CMS\Generators\EntityGenerator as Entity;
use CMS\Generators\RepositoryGenerator as Repository;
use CMS\Generators\ValidatorGenerator as Validator;
use CMS\Generators\ManagerGenerator as Manager;
use CMS\Generators\ControllerGenerator as Controller;
use CMS\Generators\RouteGenerator as Route;

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

    protected $layers = [
        'entity',
        'repository',
        'validator',
        'manager',
        'controller',
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
    public function __construct(Entity $Entity, Repository $Repository, Validator $Validator, Manager $Manager, Controller $Controller, Route $Route)
    {
        parent::__construct();

        $this->entity = $Entity;

        $this->repository = $Repository;

        $this->validator = $Validator;

        $this->manager = $Manager;

        $this->controller = $Controller;

        $this->route = $Route;
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

        $this->documentation = $this->confirm('Generate documentation?' , 'yes');

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
                $isDir = true;

                if(!is_dir('database/migrations/'.$this->table))
                {

                    if(!mkdir('database/migrations/'.$this->table, 0766 , true))
                    {
                        $isDir = false;

                        $this->error('No se pudo crear el directorio app/CMS/database/'.$this->table);
                    }

                }

                if($isDir)
                {
                    $nameMigration = 'create_'.$this->table.'_table_'.time();

                    $this->callSilent('make:migration', [
                        'name' => $nameMigration,
                        '--create' => $this->table,
                        '--table' => $this->table,
                        '--path' => 'database/migrations/'.$this->table
                    ]);

                    $this->info('Migration created in: database/migrations/'.$this->table.'/'.$nameMigration.',php');

                    if($this->confirm('Run migration?' , 'yes'))
                    {

                        try
                        {

                            $this->call('migrate', [
                                '--path' => 'database/migrations/'.$this->table
                            ]);

                        }catch (\Exception $e)
                        {
                            $this->error($e->getMessage());

                        }

                    }

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
                exec('apidoc -i app/Http/Controllers/CMS/ -f "'.$this->base.'Controller.php" -o public/docs/'.snake_case(str_plural($this->base)));

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

        if($layer == 'entity')
        {
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
