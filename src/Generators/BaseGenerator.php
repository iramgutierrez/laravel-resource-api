<?php

namespace Iramgutierrez\API\Generators;

use Illuminate\Support\Facades\File;

abstract class BaseGenerator{


    use \Illuminate\Console\AppNamespaceDetectorTrait;

    protected $path;

    protected $entity;

    protected $filename;

    protected $pathname;

    protected $pathfile;

    protected $layer;

    protected $namespace;

    protected $appNamespace;

    public function __construct()
    {

    }

    const EXTENSION_FILE = '.php';

    const PROJECT_NAME = 'iramgutierrez/generate-resource-api';

    const AUTHOR_NAME = 'Iram Gutiérrez';

    const AUTHOR_EMAIL = 'iramgutzglez@gmail.com';

    public function setEntity($entity)
    {
        $this->entity = $entity;

        $this->generateFileName();

    }

    public function setPath($pathname)
    {
        $this->pathname = $pathname;

        $this->appNamespace = $this->getAppNamespace().$this->pathname.'\\';

        $this->namespace = $this->appNamespace.$this->pathfile.'\\';

        $this->path = app_path().'/'.$this->pathname.'/';

    }

    public function checkExists()
    {
        return file_exists($this->filename);
    }

    public function getFilename()
    {
        return $this->filename;
    }

    private function generateFileName()
    {
        $this->filename = $this->path.$this->pathfile.'/'.$this->entity.$this->layer.self::EXTENSION_FILE;
    }

    protected function generateFile($code)
    {
        
        if(!File::isDirectory($this->path))
        {
            File::makeDirectory($this->path);
        }

        if(File::isWritable($this->path)){

            if(!File::isDirectory($this->path.$this->pathfile))
            {
                File::makeDirectory($this->path.$this->pathfile);
            }

            if(File::isWritable($this->path.$this->pathfile))
            {
                File::put($this->filename , $code);

                if(File::exists($this->filename))
                {
                    return "File ".$this->filename." created successfully";
                }
                else
                {
                    return "no se pudo crear ".$this->filename;
                }
            }

            return "No se puede escribir o no existe ".$this->path.$this->pathfile;
        }

        return "No se puede escribir o no existe ".$this->path;
    }

    abstract function generate();
}