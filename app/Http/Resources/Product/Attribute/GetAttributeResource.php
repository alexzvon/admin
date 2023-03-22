<?php

namespace App\Http\Resources\Product\Attribute;

use Illuminate\Http\Resources\Json\JsonResource;

class GetAttributeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        dd($this->resource);
        $getAttributeWithI18n = $this->resource['getAttributeWithI18n'];
        $arrIdAttribute = $this->resource['arrIdAttribute'];
        $getLinkAttributeOption = $this->resource['getLinkAttributeOption'];
        $linkAttributeRelation = $this->resource['linkAttributeRelation'];

        $prepare = [
            'selectedAttributes' => $this->selectedAttributes($getAttributeWithI18n),
            'selectedOptions' => $this->selectedOptions($arrIdAttribute, $getLinkAttributeOption),
//            'idAttributeRelation' => $this->arrIdAttributeRelation($this->resource['arrIdAttributeRelation']),
            'idAttributeRelation' => $this->resource['arrIdAttributeRelation'],
            'linkAttributeRelation' => $this->arrLinkAttributeRelation($linkAttributeRelation),
        ];

//        dd($prepare);

        return ['data' => $this->prepareData($prepare)];
    }

    protected function prepareData($prepare): array
    {
        $result = [];

        foreach ($prepare['selectedAttributes'] as $attr) {
            $result[] = [
                'attr' => $attr,
                'opt' => $prepare['selectedOptions'][$attr['id']],
                'link' => $prepare['linkAttributeRelation'][$attr['id']],
                'attrRelation' => $prepare['idAttributeRelation'][$attr['id']],
            ];
        }

        return $result;
    }

    /**
     * @param $linkAttributeRelation
     * @return array
     */
    private function arrLinkAttributeRelation($linkAttributeRelation): array
    {
        $result = [];

        foreach ($linkAttributeRelation as $link) {
            $result[$link->attribute_id] = $link;
        }

        return $result;
    }

    /**
     * @param $arrIdAttributeRelation
     * @return array
     */
    private function arrIdAttributeRelation($arrIdAttributeRelation): array
    {
        $result = [];

        foreach ($arrIdAttributeRelation as $key=>$attr) {
            $result['attr_' . $key] = $attr;
        }

        return $result;
    }

    /**
     * @param $arrIdAttribute
     * @param $getLinkAttributeOption
     * @return array
     */
    private function selectedOptions($arrIdAttribute, $getLinkAttributeOption): array
    {
        $result = [];

        foreach ($arrIdAttribute as $attribute) {
            $attr = [];

            foreach ($getLinkAttributeOption as $linkOption) {
                $option = $linkOption->option;

                if ($attribute === $option->attribute_id) {
                    $i18n = [];

                    foreach ($option->i18n as $itemI18) {
                        $i18n[] = [
                            'option_id' => $itemI18->option_id,
                            'language_code' => $itemI18->language_code,
                            'value' => $itemI18->value,
                        ];
                    }

                    $opt = [
                        'id' => $option->id,
                        'attribute_id' => $option->attribute_id,
                        'enabled' => $option->enabled,
                        'position' => $option->position,
                        'value' => [],
                        'i18n' => $i18n,
                    ];

                    $attr[] = $opt;
                }
            }

            $result[$attribute] = $attr;
        }

        return $result;
    }

    /**
     * @param $getAttributeWithI18n
     * @return array
     */
    private function selectedAttributes($getAttributeWithI18n): array
    {
        $result = [];

        foreach ($getAttributeWithI18n as $item) {
            $i18n = [];
            foreach ($item->i18n as $itemI18n) {
                $i18n[] = [
                    'attribute_id' => $itemI18n->attribute_id,
                    'language_code' => $itemI18n->language_code,
                    'title' => $itemI18n->title,
                ];
            }

            $result[] = [
                'id' => $item->id,
                'layout_class' => $item->layout_class,
                'selectable' => $item->selectable,
                'enabled' => $item->enabled,
                'position' => $item->position,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'i18n' => $i18n,
            ];
        }

        return $result;
    }
}
