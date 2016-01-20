<?php

namespace CMS\Validators;

use CMS\Entities\BaseEntity as Entity;


/**
 * Class BaseValidator
 * @package CMS\Validators
 */
abstract class BaseValidator
{
    /**
     * @var
     */
    protected $rules;

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var
     */
    protected $errors;

    /**
     * @param Entity $Entity
     */
    public function __construct(Entity $Entity)
    {
        $this->entity = $Entity;
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
     * @return mixed
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

        $validation = \Validator::make($data, $rules);

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