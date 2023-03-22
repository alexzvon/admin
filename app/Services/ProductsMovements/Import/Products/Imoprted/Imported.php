<?php

declare(strict_types = 1);

namespace App\Services\ProductsMovements\Import\Products\Imported;

use App\Models\Shop\Product\Product;
use App\Services\ProductsMovements\Import\Interfaces\IImported;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Imported
 *
 * @package App\Services\ProductsMovements\Import\Products\Imported
 */
abstract class Imported implements IImported
{
    /**
     * @var Product $product
     */
    protected $product;

    /**
     * Imported constructor.
     *
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Notify if product was created. It has to be updated otherwise.
     *
     * @return bool
     */
    abstract public function isCreated(): bool;

    /**
     * Returns $product
     *
     * @return Model
     */
    public function getObject(): Model
    {
        return $this->product;
    }
}
