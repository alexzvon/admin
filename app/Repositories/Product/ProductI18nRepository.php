<?php
declare(strict_types=1);

namespace App\Repositories\Product;

use App\Repositories\CoreRepository;
use App\Models\Shop\Product\ProductI18n as Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductI18nRepository extends CoreRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getModelClass(): string
    {
        return Model::class;
    }

    /**
     * @param  string  $search
     * @param  array  $not_in
     * @param  int  $limit
     * @param  int  $offset
     * @return Collection|null
     */
    public function getAddProducts(string $search = '', array $not_in = [], int $limit = 0, $offset = 0): ?Collection
    {
        $result = null;

        $limit = 0 === $limit ? 50 : $limit;

        if (0 === $offset && 0 === strlen($search)) {
            $result = $this->startConditions()
                ->select(DB::raw('product_id as id'), 'title')
                ->whereNotIn('product_id', $not_in)
                ->orderBy('product_id')
                ->limit($limit)
                ->get();
        } else if (0 === strlen($search)) {
            $result = $this->startConditions()
                ->select(DB::raw('product_id as id'), 'title')
                ->whereNotIn('product_id', $not_in)
                ->orderBy('product_id')
                ->limit($limit)
                ->offset($offset)
                ->get();
        } else {
            $result = $this->startConditions()
                ->select(DB::raw('product_id as id'), 'title')
                ->where('title', 'ILIKE', '%'.$search.'%')
                ->whereNotIn('product_id', $not_in)
                ->orderBy('product_id')
                ->limit($limit)
                ->offset($offset)
                ->get();
        }

        return $result;
    }
}