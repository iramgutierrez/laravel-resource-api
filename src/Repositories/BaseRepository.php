<?php

namespace IramGutierrez\API\Repositories;

use IramGutierrez\API\Entities\BaseEntity as Entity;

/**
 * Class BaseRepository
 * @package IramGutierrez\API\Repositories
 */
abstract class BaseRepository
{
    /**
     * @var Entity
     */
    protected $entity;

    /**
     * BaseRepository constructor.
     * @param Entity $Entity
     */
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

    /**
     * @param $id
     * @return Entity
     */
    public function find($id)
    {
        return $this->entity->find($id);
    }
}