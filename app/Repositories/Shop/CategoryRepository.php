<?php

namespace App\Repositories\Shop;

use Illuminate\Support\Collection;
use MosseboShopCore\Repositories\Shop\CategoryRepository as BaseCategoryRepository;
use App\Models\Shop\Category\Category;

class CategoryRepository extends BaseCategoryRepository
{
    public function getTree()
    {
        return $this->getCollection()->toTree();
    }

    public function getCollectionRaw(): Collection
    {
        return Category::/*enabled()
            ->*/with('i18n')
            ->withProductCount()
            ->with('image')
            ->get();
    }

    /**
     * Перечень котегорий
     * @return array
     */
    public function getOptionsTree()
    {
        $options = [];
        $opt = [];
        $cat = Category::with(['i18n'])->get();

        foreach($cat as $item) {
            if(is_null($item->parent_id)) {
                $opt[$item->id] = $item->i18n[0]->title;
            }
        }

        foreach($opt as $op_id=>$op_name) {
            $op = [];
            foreach($cat as $item) {
                if($item->parent_id == $op_id) {
                    $op[] = [
                        'label' => $item->i18n[0]->title,
                        'value' => $item->id,
                    ];
                }
            }
            $options[] = [
                'label' => '- ' . $op_name,
                'value' => $op_id,
            ];
            $options = array_merge($options, $op);
        }

        return ['options' => $options];
    }
}
