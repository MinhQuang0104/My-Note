<?php

namespace App\Http\Requests;

class UpdateGoalEntryRequest extends StoreGoalEntryRequest
{
    public function rules(): array
    {
        return [
            'value' => ['sometimes', 'required', 'numeric', 'min:0'],
            'note' => ['nullable', 'string'],
        ];
    }
}
