<?php

namespace App\Support\Traits\Models;

use App\Models\Shop\Style\Style;

trait HasStyles
{
    public function styles()
    {
        return $this->hasManyThrough(
            Style::class, $this->styleRelationModel,
            $this->relationFieldName, 'id', 'id', 'style_id'
        );
    }

    public function styleRelations()
    {
        return $this->hasMany($this->styleRelationModel, $this->relationFieldName);
    }

    /**
     * Сохранение категорий товара.
     *
     * @param array $categoryIds
     */
    protected function _saveStyles(array $stylesIds = [])
    {
        $styleRelations = $this->styleRelations();
        $styleRelations->delete();

        foreach ($stylesIds as $styleId) {
            $styleRelations->save(new $this->styleRelationModel(['style_id' => $styleId]));
        }
    }
}