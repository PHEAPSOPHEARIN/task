<?php

namespace App\Http\Requests\Ability;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleAbilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        // return request()->user()->token('role:set-abilities')
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'abilityIds' => 'required|array',
            'abilityIds.*' => 'required|exists:abilities,id',
        ];
    }
}