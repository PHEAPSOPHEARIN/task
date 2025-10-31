<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if (request()->method() == 'PATCH') {
            return [
                'name' => 'sometimes|string',
                'description' => 'sometimes|string'
            ];
        };
        return [
            'name' => 'required|string',
            'description' => 'required|string'
        ];

    }
}