<?php

namespace CMS\Generators;

abstract class BaseGenerator{

    protected $entity;

    protected $filename;

    protected $pathfile;

    protected $layer;

    protected $namespace;

    const EXTENSION_FILE = '.php';

    const PROJECT_NAME = 'iramgutierrez/generate-resource-api';

    const AUTHOR_NAME = 'Iram Gutiérrez';

    const AUTHOR_EMAIL = 'iramgutzglez@gmail.com';

    public function setEntity($entity)
    {
        $this->entity = $entity;

        $this->generateFileName();

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
        $this->filename = $this->pathfile.$this->entity.$this->layer.self::EXTENSION_FILE;
    }

    abstract function generate();
}