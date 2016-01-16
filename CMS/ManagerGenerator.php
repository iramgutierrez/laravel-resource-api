<?php

namespace CMS\Generators;

use Memio\Memio\Config\Build;
use Memio\Model\File;
use Memio\Model\Object;
use Memio\Model\Property;
use Memio\Model\Method;
use Memio\Model\Argument;
use Memio\Model\Constant;
use Memio\Model\FullyQualifiedName;
use Memio\Model\Phpdoc\LicensePhpdoc;

use CMS\Managers\BaseManager;

class ManagerGenerator extends BaseGenerator{

    protected $pathfile = 'app/CMS/Managers/';

    protected $layer = 'Manager';

    protected $namespace = 'CMS\\Managers\\';

    public function generate()
    {
        $repository = File::make($this->filename)
            ->setLicensePhpdoc(new LicensePhpdoc(self::PROJECT_NAME, self::AUTHOR_NAME, self::AUTHOR_EMAIL))
            ->addFullyQualifiedName(new FullyQualifiedName("CMS\Entities\\".$this->entity."Entity as Entity"))
            ->addFullyQualifiedName(new FullyQualifiedName("CMS\Validators\\".$this->entity."Validator as Validator"))
            ->setStructure(
                Object::make($this->namespace.$this->entity.$this->layer)
                    ->extend(new Object(BaseManager::class))
                    ->addMethod(
                        Method::make('__construct')
                            ->addArgument(new Argument('Entity', 'Entity'))
                            ->addArgument(new Argument('Validator', 'Validator'))
                            ->setBody('        return parent::__construct($Entity , $Validator);')
                    )
                    ->addMethod(
                        Method::make('save')
                            ->addArgument(new Argument('array', 'data'))
                            ->setBody('        return parent::save($data);')
                    )
                    ->addMethod(
                        Method::make('update')
                            ->addArgument(new Argument('array', 'data'))
                            ->setBody('        return parent::update($data);')
                    )
                    ->addMethod(
                        Method::make('delete')
                            ->setBody('        return parent::delete();')
                    )
                    ->addMethod(
                        Method::make('prepareData')
                            ->setBody('        return parent::prepareData();')
                    )
            );

        $prettyPrinter = Build::prettyPrinter();
        $generatedCode = $prettyPrinter->generateCode($repository);

        $myfile = fopen($this->filename, "w") or die("Unable to open file!");
        fwrite($myfile, $generatedCode);
        fclose($myfile);

        return "File ".$this->filename." created successfully";
    }
}