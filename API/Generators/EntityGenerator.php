<?php

namespace Iramgutierrez\API\Generators;

use Memio\Memio\Config\Build;
use Memio\Model\File;
use Memio\Model\Object;
use Memio\Model\Property;
use Memio\Model\Method;
use Memio\Model\Argument;
use Memio\Model\Constant;
use Memio\Model\FullyQualifiedName;
use Memio\Model\Phpdoc\LicensePhpdoc;

use Iramgutierrez\API\Entities\BaseEntity;

class EntityGenerator extends BaseGenerator{

    protected $pathfile = 'Entities';

    protected $layer = 'Entity';

    protected $table;

    public function setTable($table)
    {
        $this->table = $table;
    }

    public function generate()
    {
        $entity = File::make($this->filename)
            ->setLicensePhpdoc(
                new LicensePhpdoc(self::PROJECT_NAME, self::AUTHOR_NAME, self::AUTHOR_EMAIL)
            )
            ->addFullyQualifiedName(
                new FullyQualifiedName(\Illuminate\Database\Eloquent\Collection::class)
            )
            ->addFullyQualifiedName(new FullyQualifiedName(BaseEntity::class))
            ->setStructure(
                Object::make($this->namespace.$this->entity.$this->layer)
                    ->extend(
                        new Object(BaseEntity::class)
                    )
                    ->addProperty(
                        Property::make('table')
                            ->makeProtected()
                            ->setDefaultValue("'".$this->table."'")
                    )
                    ->addProperty(
                        Property::make('fillable')
                            ->makeProtected()
                            ->setDefaultValue("['id' , 'name']")
                    )
                    ->addProperty(
                        Property::make('hidden')
                            ->makeProtected()
                            ->setDefaultValue("[]")
                    )
                    ->addProperty(
                        Property::make('appends')
                            ->makeProtected()
                            ->setDefaultValue("[]")
                    )
            );

        $prettyPrinter = Build::prettyPrinter();
        $generatedCode = $prettyPrinter->generateCode($entity);

        return $this->generateFile($generatedCode);


    }
}