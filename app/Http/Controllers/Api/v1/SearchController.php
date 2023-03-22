<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\ApiRequest;
use App\Http\Resources\Shop\Product\Api\ProductSearchResource;
use App\Models\Shop\Product\Product;
use Config;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

/**
 * Class SearchController
 *
 * @package App\Http\Controllers\Api\v1
 */
class SearchController extends ApiController
{
    /**
     * SearchController constructor.
     *
     * @param ApiRequest $request
     */
    public function __construct(ApiRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function query(Request $request): array
    {
        $quantity = 50;

        if ($request->has('quantity')) {
            $rawQuantity = $request->get('quantity');
            if (intval($rawQuantity)) {
                $quantity = intval($rawQuantity);
                $quantity = min($quantity, 100);
            }
        }


        $ids = $this->searchResultToIds(
            $this->search($request->input('q'))->take($quantity)
        );

        $products = Product::whereIn('id', $ids)->get();

        return [
            'status'   => 'success',
            'products' => ProductSearchResource::collection($products)
        ];
    }

    /**
     * @param $query
     *
     * @return Collection
     */
    protected function search($query): Collection
    {
        $r = 1;
        return Product::search($query, function($client, $query, $params) {
            $query = mb_strtolower($query);

            $params['body'] = [
                'from'  => 0,
                'size'  => 10000,
                'query' => [
                    'wildcard' => [
                        'index' => "*{$query}*",
                    ]
                ]
            ];

            $result = $client->search($params);

            if (isset($result['hits']['total']) && $result['hits']['total'] > 0) {
                return $result;
            }

            $params['body'] = [
                'from'  => 0,
                'size'  => 10000,
                'query' => [
                    'match' => [
                        'index' => [
                            'query'     => "*{$query}*",
                            'fuzziness' => 'auto',
                            'operator'  => 'and',
                        ]
                    ]
                ]
            ];

            return $client->search($params);
        })->get();
    }

    /**
     * @param $result
     *
     * @return array
     */
    protected function searchResultToIds($result): array
    {
        return array_column($result->toArray(),'id');
    }

    /**
     * @param $query
     *
     * @return array
     */
    protected function searchIds($query): array
    {
        return $this->searchResultToIds($this->search($query));
    }
}
