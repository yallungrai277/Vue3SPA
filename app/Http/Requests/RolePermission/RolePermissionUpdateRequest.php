<?php

namespace App\Http\Requests\RolePermission;

use App\Models\Role;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RolePermissionUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'roles_permission' => [
                'array',
                function ($attribute, $value, $fail) {
                    $indexes = array_keys($value);
                    collect($indexes)->each(function ($value) use ($fail) {
                        if (!Role::find($value)) {
                            $fail('The role with an id of ' . $value . ' is not found');
                        }
                    });
                }
            ],
            'roles_permission.*' => [
                'array'
            ],
            'roles_permission.*.*' => [
                Rule::exists('permissions', 'id')
            ]
        ];
    }
}