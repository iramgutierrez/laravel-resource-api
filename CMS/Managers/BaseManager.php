<?php

namespace CMS\Managers;

use CMS\Entities\BaseEntity as Entity;
use CMS\Validators\BaseValidator as Validator;

/**
 * Class BaseManager
 * @package CMS\Managers
 */
class BaseManager
{
    /**
     * @var
     */
    protected $data;

    /**
     * @var Entity
     */
    protected $entity;


    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @param Entity $Entity
     * @param Validator $Validator
     */
    public function __construct(Entity $Entity, Validator $Validator)
    {
        $this->entity = $Entity;

        $this->validator = $Validator;

    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function save(array $data)
    {
        $this->data = $data;

        $this->prepareData();

        $isValid = $this->validator->isValid($this->data);

        if ($isValid) {
            $this->entity->fill($this->data);

            $this->entity->save();

            return $this->entity;

        } else {

            return $this->validator->getErrors();

        }

    }

    public function update(array $data)
    {

        $this->data = $data;

        $this->prepareData();

        $this->validator->setEntity($this->entity);

        $isValid = $this->validator->isValid($this->data);

        if ($isValid) {

            $fillable = $this->entity->getFillable();

            $data = $this->data;

            foreach ($data as $k => $v) {
                if (in_array($k, $fillable)) {
                    $this->entity->$k = $v;
                }
            }

            $this->entity->update();

            return $this->entity;

        } else {

            return $this->validator->getErrors();

        }
    }

    public function delete()
    {
        if($this->entity->exists)
        {

            return $this->entity->delete();
        }
        else
        {
            return false;
        }
    }

    /**
     *
     */

    public function prepareData()
    {
        $data = $this->data;

        $this->data = $data;

    }

    /**
     * @param Entity $Entity
     */
    public function setEntity(Entity $Entity)
    {
        $this->entity = $Entity;
    }
}