<?php

namespace App\Http\Requests;

class UpdateGoalRequest extends StoreGoalRequest
{
    public function rules(): array
    {
        return collect(parent::rules())->map(fn (array $rules) => array_values(array_diff($rules, ['required'])))->all();
    }
}
