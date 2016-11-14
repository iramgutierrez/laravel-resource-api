<?php

namespace IramGutierrez\API\Validators;

use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Validator;
use IramGutierrez\API\Entities\BaseEntity as Entity;


/**
 * Class BaseValidator
 * @package IramGutierrez\API\Validators
 */
abstract class BaseValidator
{

    /**
     * @var
     */
    protected $data;

    /**
     * @var
     */
    protected $rules;

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var MessageBag
     */
    protected $errors;

    /**
     * BaseValidator constructor.
     * @param Entity $Entity
     */
    public function __construct(Entity $Entity)
    {
        $this->entity = $Entity;

        $this->errors = new MessageBag();
    }

    /**
     * @return mixed
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @return mixed
     */
    public function getCreateRules()
    {
        return $this->getRules();
    }

    /**
     * @return mixed
     */
    public function getUpdateRules()
    {
        return $this->getRules();
    }

    /**
     * @return MessageBag
     */
    public function getErrors()
    {
        
        return $this->errors;
    }

    /**
     * @param $data
     * @return bool
     */
    public function isValid($data)
    {
        $this->data = $data;

        if ($this->entity->exists) {
            $this->rules = $this->getUpdateRules();
        } else {
            $this->rules = $this->getCreateRules();
        }

        $rules = $this->getRules();

        $validation =  Validator::make($data, $rules);

        if ($validation->passes()) return true;

        $this->errors = $validation->messages();

        return false;
    }

    /**
     * @param Entity $Entity
     */
    public function setEntity(Entity $Entity)
    {
        $this->entity = $Entity;
    }

}