<?php

namespace App\Http\Requests\Territory\ShowRoomRoom;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $arrRoles = [];

        foreach($this->user()->roles as $roles) {
            $arrRoles[] = $roles->id;
        }

        return in_array(1, $arrRoles) || in_array(2, $arrRoles);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'group_id' => 'required|integer',
            'cities_id' => 'required|integer',
            'name' => 'required',
            'address' => 'required',
            'work_time' => 'required',
            'status' => 'boolean',
        ];
    }
}
