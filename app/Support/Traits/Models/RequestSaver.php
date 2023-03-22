<?php

namespace App\Support\Traits\Models;

use App\Contracts\Models\ITypedSources;

trait RequestSaver
{
    /**
     * Сохранение, используя данные, полученные из запроса.
     *
     * @param array $data
     *
     * @return self
     */
    public function saveFromRequestData(array $data): self
    {
        \DB::transaction(function() use($data) {
            $fillable = $this->getFillableData($data);

            foreach (array_keys($fillable) as $toUnset) {
                unset($data[$toUnset]);
            }

            if ($this->id) {
                $this->update($fillable);
            } else {
                $this->fill($fillable)->save();
            }

            $this->handleRelationData($data, ITypedSources::TYPE_REQUEST);
        });

        return $this;
    }

    /**
     * Сохранение, используя данные, полученные из импорта.
     *
     * @param array $data
     *
     * @return self
     */
    public function saveFromImportData(array $data): self
    {
        \DB::transaction(function() use($data) {
            $fillable = $this->getFillableData($data);

            foreach (array_keys($fillable) as $toUnset) {
                unset($data[$toUnset]);
            }

            if ($this->id) {
                $this->update($fillable);
            } else {
                $this->fill($fillable)->save();
            }

            $this->handleRelationData($data, ITypedSources::TYPE_IMPORT);
        });

        return $this;
    }

    /**
     * Сохранение связанных моделей, либо данных, требующих дополнительной обработки.
     *
     * @param array $data
     * @param int   $type
     */
    protected function handleRelationData(array $data, int $type): void
    {
        if ($type === ITypedSources::TYPE_IMPORT) {
            $stepsToBeSaved = $this->needsToSaveFromImport;
        } else {
            $stepsToBeSaved = $this->needsToSaveFromRequest;
        }

        foreach ($stepsToBeSaved ?: [] as $stepName) {
            $methodName = '_save' . camelize($stepName);

            if (method_exists($this, $methodName)) {
                if (isset($data[$stepName])) {
                    call_user_func([$this, $methodName], $data[$stepName]);
                }
                else {
                    call_user_func([$this, $methodName]);
                }
            }
        }
    }
}
