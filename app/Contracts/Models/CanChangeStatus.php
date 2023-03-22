<?php

namespace App\Contracts\Models;

interface CanChangeStatus
{
  public function statusToggle();
}