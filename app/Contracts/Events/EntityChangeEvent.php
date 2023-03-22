<?php

namespace App\Contracts\Events;

interface EntityChangeEvent
{
    public function getName();

    public function getEntityName();

    public function getModel();
}