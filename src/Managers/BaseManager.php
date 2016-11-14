<?php

namespace IramGutierrez\API\Managers;

use Illuminate\Contracts\Support\MessageBag;
use IramGutierrez\API\Entities\BaseEntity as Entity;
use IramGutierrez\API\Validators\BaseValidator as Validator;

/**
 * Class BaseManager
 * @package IramGutierrez\API\Managers
 */
abstract class BaseManager
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
     * @param array $data
     * @return Entity|Illuminate\Contracts\Support\MessageBag
     */
    public function save(array $data)
    {
        $this->data = $data;

        $this->prepareData();

        $isValid = $this->validator->isValid($this->data);

        if ($isValid)
        {
            $this->entity->fill($this->data);
            $this->entity->save();
            return $this->entity;
        }
        else
        {
            return $this->validator->getErrors();
        }

    }

    /**
     * @param array $data
     * @return Entity|Illuminate\Contracts\Support\MessageBag
     */
    public function update(array $data)
    {

        $this->validator->setEntity($this->entity);

        return $this->save($data);
    }

    /**
     * @return bool
     */
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

        /*TO DO*/

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