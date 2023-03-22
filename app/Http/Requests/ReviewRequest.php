<?php

namespace App\Http\Requests;
use App\Support\Traits\Requests\HasDates;

class ReviewRequest extends ApiRequest
{
    use HasDates;

    protected function prepareForValidation()
    {
        $this->prepareDate('created_at');
        $this->prepareDate('updated_at');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function getEntityRules()
    {
        $checkDate = function ($attribute, $value, $fail) {
            return $this->checkDate($attribute, $value, $fail);
        };

        return [
            'rate'          => 'bail|required|between:1,5',
            'advantages'    => 'max:512|nullable',
            'disadvantages' => 'max:512|nullable',
            'comment'       => 'bail|required|max:2048',
            'usage_time'    => 'in:month,half-year,year|nullable',
            'created_at'    => $checkDate,
            'updated_at'    => $checkDate,
        ];
    }
}
