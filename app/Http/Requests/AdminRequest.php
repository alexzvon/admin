<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Support\Traits\Requests\HasImages;

class AdminRequest extends ApiRequest
{
    use HasImages;

    protected $model = \App\Models\Admin::class;
    protected $permissionsNamespace = 'system.admins';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function getEntityRules()
    {
        $adminsTableName = \Config::get('tables.Admins');

        $rules = [
            'name'    => 'bail|required|max:255',
            'email'   => ['bail', 'required', 'email', 'max:255'],
            'enabled' => 'boolean',
            'roles'   => function($attribute, $value, $fail) {
                foreach ($this->input('roles') as $roleId) {
                    $role = \AdminRoles::where('id', $roleId)->first();

                    if (! $role) {
                        return $fail(\Lang::get("validation.roles.exists"));
                    }

                    if (! $this->getRequestUser()->hasEnoughImportance($role->importance)) {
                        return $fail(\Lang::get("validation.roles.importance"));
                    }
                }
            },
        ];

        if (! is_null($this->input('new_password'))) {
            $rules['old_password'] = [
                'bail', 'required',

                function($attribute, $value, $fail) {
                    if (! \Hash::check($this->input('old_password'), $this->getEntity()->password)) {
                        return $fail(\Lang::get("validation.password.old"));
                    }
                },
            ];

            $rules['new_password'] = 'bail|min:8|max:255|confirmed';
        }

        if ($this->isCreate()) {
            $rules['email'][] = Rule::unique($adminsTableName);
        }

        if ($this->isUpdate()) {
            $rules['email'][] = Rule::unique($adminsTableName)->ignore($this->getId());
        }

        $this->setImagesRule($rules);

        return $rules;
    }
}
