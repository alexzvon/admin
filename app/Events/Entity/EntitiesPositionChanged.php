<?php

namespace App\Events\Entity;

class EntitiesPositionChanged
{
    protected $modelClassName;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($modelClassName)
    {
        $this->modelClassName = $modelClassName;
    }

    public function getModelClassName()
    {
        return $this->modelClassName;
    }
}