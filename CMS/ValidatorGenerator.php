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

use CMS\Validators\BaseValidator;

class ValidatorGenerator extends BaseGenerator{

    protected $pathfile = 'app/CMS/Validators/';

    protected $layer = 'Validator';

    protected $namespace = 'CMS\\Validators\\';

    public function generate()
    {
        $repository = File::make($this->filename)
            ->setLicensePhpdoc(new LicensePhpdoc(self::PROJECT_NAME, self::AUTHOR_NAME, self::AUTHOR_EMAIL))
            ->addFullyQualifiedName(new FullyQualifiedName("CMS\Entities\\".$this->entity."Entity as Entity"))
            ->setStructure(
                Object::make($this->namespace.$this->entity.$this->layer)
                    ->extend(new Object(BaseValidator::class))
                    ->addProperty(
                        Property::make('rules')
                            ->makeProtected()
                            ->setDefaultValue("[]")
                    )
                    ->addMethod(
                        Method::make('__construct')
                            ->addArgument(new Argument('Entity', 'Entity'))
                            ->setBody('        parent::__construct($Entity);')
                    )
                    ->addMethod(
                        Method::make('getCreateRules')
                            ->setBody('        return parent::getCreateRules();')
                    )
                    ->addMethod(
                        Method::make('getUpdateRules')
                            ->setBody('        return parent::getUpdateRules();')
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