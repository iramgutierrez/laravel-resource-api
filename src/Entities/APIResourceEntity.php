<?php

namespace Iramgutierrez\API\Entities;

class APIResourceEntity extends BaseEntity
{
    protected $table = 'api_resources';

    protected $fillable = ['id' ,'base' ,'table' , 'prefix' , 'documentation' , 'migration' , 'run_migration', 'route' , 'middlewares'];

    protected $hidden = [];

    protected $appends = [];
}