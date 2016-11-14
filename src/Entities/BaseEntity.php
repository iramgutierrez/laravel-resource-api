<?php

namespace IramGutierrez\API\Entities;

use Illuminate\Database\Eloquent\Model;

abstract class BaseEntity extends Model
{

    protected $fillable = ['id' , 'name'];

    protected $hidden = [];

    protected $appends = [];

}