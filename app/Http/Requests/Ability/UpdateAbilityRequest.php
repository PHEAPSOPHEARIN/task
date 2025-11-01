<?php

namespace App\Http\Requests\Ability;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAbilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // reture request()->user()->tokenCan('ability:update');
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $ability = $this->route('ability');
        if (request()->method() == 'PATCH') {
            return [
                'action' => 'sometimes|unique:abilities,action,' .$ability->id,
            ];
        }
        return [
             'action' => 'sometimes|unique:abilities,action,' .$ability->id,
        ];
    }
}