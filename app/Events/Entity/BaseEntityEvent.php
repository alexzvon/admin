<?php

namespace App\Events\Entity;

use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\Events\EntityChangeEvent;

class BaseEntityEvent implements EntityChangeEvent
{
    use SerializesModels;

    protected $model;
    protected $name;
    protected $entityName;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->name = lcfirst(str_replace('Entity', '', $this->getClassShortName($this)));
        $this->entityName = $this->getClassShortName($model);
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getModelClassName()
    {
        return get_class($this->model);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEntityName()
    {
        return $this->entityName;
    }

    protected function getClassShortName($obj)
    {
        return (new \ReflectionClass($obj))->getShortName();
    }
}
