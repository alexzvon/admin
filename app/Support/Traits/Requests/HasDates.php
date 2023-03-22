<?php

namespace App\Support\Traits\Requests;

trait HasDates
{
    protected function prepareDate($attribute)
    {
        $value = $this->json->get($attribute);

        if (! $value || is_numeric($value)) {
            return;
        }

        $dateArr = date_parse_from_format('d-m-Y H:i:s', $value);

        if (empty($dateArr['warnings']) && empty($dateArr['errors'])) {
            $date = new \DateTime();
            $date->setDate($dateArr['year'], $dateArr['month'], $dateArr['day']);
            $date->setTime($dateArr['hour'], $dateArr['minute'], $dateArr['second']);
            $date = $date->getTimestamp();
        }
        else {
            $date = 'error';
        }

        $this->json->set($attribute, $date);
    }

    public function checkDate($attribute, $value, $fail)
    {
        if ($value === 'error') {
            return $fail('Некорректная дата.');
        }
    }
}