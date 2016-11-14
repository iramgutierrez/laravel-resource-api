<?php namespace IramGutierrez\API;

use Illuminate\Support\ServiceProvider;



use IramGutierrez\API\Generators\EntityGenerator as Entity;
use IramGutierrez\API\Generators\MigrationGenerator;
use IramGutierrez\API\Generators\RepositoryGenerator as Repository;
use IramGutierrez\API\Generators\ValidatorGenerator as Validator;
use IramGutierrez\API\Generators\ManagerGenerator as Manager;
use IramGutierrez\API\Generators\ControllerGenerator as Controller;
use IramGutierrez\API\Generators\RouteGenerator as Route;
use IramGutierrez\API\Generators\MigrationGenerator as Migration;
use IramGutierrez\API\Generators\DocumentationGenerator as Documentation;
use IramGutierrez\API\Entities\APIResourceEntity as APIResource;

/**
 * Class APIServiceProvider
 * @package IramGutierrez\API
 */
class APIServiceProvider extends ServiceProvider {


    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var Controller
     */
    protected $controller;

    /**
     * @var Route
     */
    protected $route;

    /**
     * @var Migration
     */
    protected $migration;

    /**
     * @var Documentation
     */
    protected $documentation;

    /**
     * APIServiceProvider constructor.
     * @param Application $app
     * @param Entity $Entity
     * @param Repository $Repository
     * @param Validator $Validator
     * @param Manager $Manager
     * @param Controller $Controller
     * @param Route $Route
     * @param Migration $Migration
     * @param Documentation $Documentation
     */
    /*public function __construct($app, Entity $Entity, Repository $Repository, Validator $Validator, Manager $Manager, Controller $Controller, Route $Route , Migration $Migration , Documentation $Documentation)
    {
        parent::__construct($app);

        $this->entity = $Entity;

        $this->repository = $Repository;

        $this->validator = $Validator;

        $this->manager = $Manager;

        $this->controller = $Controller;

        $this->route = $Route;

        $this->migration = $Migration;

        $this->documentation = $Documentation;
    }*/

    // [...]

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // [...]

        $this->publishes([
            realpath(__DIR__.'/../config/config.php') => config_path('resource_api.php'),
        ], 'config');

        $this->publishes([
            realpath(__DIR__.'/../database/migrations/') => database_path('/migrations')
        ], 'migrations');
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {

        $Entity = new Entity();
        $Repository = new Repository();
        $Validator = new Validator();
        $Manager = new Manager();
        $Controller = new Controller();
        $Route = new Route();
        $Migration = new Migration();
        $Documentation = new Documentation();

        $this->mergeConfigFrom(
            realpath(__DIR__.'/../config/config.php'), 'resource_api'
        );

        $this->app->singleton('command.create.resource', function() use($Entity, $Repository, $Validator, $Manager, $Controller, $Route ,$Migration , $Documentation)
        {
            return new ResourceAPI($Entity, $Repository, $Validator, $Manager, $Controller, $Route ,$Migration , $Documentation);
        });

        $this->commands(
            'command.create.resource'
        );
    }
}