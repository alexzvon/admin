<?php

namespace App\Support\Traits\Models;

trait StatusChangeable
{
    public function statusToggle()
    {
        $this->enabled = !$this->enabled;
        $this->save();
    }
}