<?php namespace App\Http\Controllers\Api\Shop\Attributor;

use App\Models\Shop\Attribute\LinkAttributeRelation;
use App\Models\Shop\Attribute\LinkAttributeRelationOption;

/**
 * Trait AttributorSync
 * @package App\Http\Controllers\Api\Shop\Attributor
 */
trait AttributorSync
{

    /**
     * @var integer
     */
    protected $source_id;

    /**
     * @var string
     */
    protected $source_name;

    /**
     * @var LinkAttributeRelation
     */
    protected $source_elements;

    /**
     * @var integer
     */
    protected $target_id;

    /**
     * @var string
     */
    protected $target_name;

    /**
     * @var LinkAttributeRelation
     */
    protected $target_elements;


    /**
     * index
     */
    public function sync()
    {
        $this->prepareInitialData();

        collect($this->source_elements)->each(function($sourceAttribute)
        {
            $targetAttribute = $this->getTargetAttribute($sourceAttribute);
            if (!$targetAttribute) {
                $targetAttribute = $this->createTargetAttribute($sourceAttribute);
            }


            if (count($sourceAttribute->linkedOptions) > 0) {
                foreach($sourceAttribute->linkedOptions as $option) {
                    $option['attribute_relation_id'] = $targetAttribute->id;
                    if (!$this->hasTargetOption($targetAttribute, $option->option_id)) {
                        $this->createTargetOption($option);
                    }
                }
            }

        });
    }


    /**
     * Создаем новое свойство для целевой модели
     *
     * @param $source_element
     * @return LinkAttributeRelation
     */
    protected function createTargetAttribute($source_element)
    {
        $created = LinkAttributeRelation::create([
            'relation_id' => $this->target_id,
            'relation' => $this->target_name,
            'attribute_id' => $source_element->attribute_id,
            'selectable' => $source_element->selectable,
            'is_products' => $source_element->is_products,
        ]);

        return LinkAttributeRelation::with('linkedOptions')->find($created->id);
    }


    /**
     * Создаем новое значение свойства для целевой модели
     *
     * @param $option
     */
    protected function createTargetOption($option)
    {
        LinkAttributeRelationOption::create([
            'option_id' => $option->option_id,
            'enabled' => true,
            'fix_price' => $option->fix_price,
            'option_product_id' => $option->option_product_id,
            'attribute_relation_id' => $option->attribute_relation_id,
        ]);
    }


    /**
     *
     * @param \Illuminate\Database\Eloquent\Model $el
     * @return bool
     */
    protected function getTargetAttribute($el)
    {
        return collect($this->target_elements)
            ->filter(function($targetEl) use($el) {
                return +$targetEl->attribute_id === +$el->attribute_id && $targetEl->linkedOptions->count() > 0;
            })
            ->first();
    }


    protected function hasTargetOption($targetAttribute, $option_id)
    {
        return collect($targetAttribute->linkedOptions)
                ->where('option_id', $option_id)
                ->count() > 0;
    }


    /**
     * Подготовка к синку свойств
     */
    protected function prepareInitialData()
    {
        $this->source_id = request('relation_source_id');
        $this->source_name = request('relation_source');

        $this->target_id = request('relation_target_id');
        $this->target_name = request('relation_target');

        $this->source_elements = LinkAttributeRelation::with('linkedOptions')
            ->where('enabled', '=', true)
            ->whereRelation($this->source_name)
            ->whereRelationId($this->source_id)
            ->get();

        $this->target_elements = LinkAttributeRelation::with('linkedOptions')
            ->where('enabled', '=', true)
            ->whereRelation($this->target_name)
            ->whereRelationId($this->target_id)
            ->get();
    }
    
}