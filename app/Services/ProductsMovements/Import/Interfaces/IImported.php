<?php

declare(strict_types = 1);

namespace App\Services\ProductsMovements\Import\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface IImported
{
    public function isCreated(): bool;

    public function getObject(): Model;
}