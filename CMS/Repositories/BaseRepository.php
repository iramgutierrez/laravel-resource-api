<?php

namespace CMS\Repositories;

use CMS\Entities\BaseEntity as Entity;

class BaseRepository
{
    protected $entity;

    public function __construct(Entity $Entity)
    {

        $this->entity = $Entity;

    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {

        return $this->entity->get();
    }

    public function findById($id)
    {
        return $this->entity->find($id);
    }
}