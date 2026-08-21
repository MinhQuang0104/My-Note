<?php

namespace App\Http\Requests;

class UpdateNoteRequest extends StoreNoteRequest
{
    public function rules(): array
    {
        return collect(parent::rules())->map(fn (array $rules) => array_values(array_diff($rules, ['required'])))->all();
    }
}
