<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfilePreferenceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'favorite_vehicle_type' => ['nullable', 'in:car,motorcycle'],
            'favorite_brand' => ['nullable', 'string', 'max:100'],
            'budget_range' => ['nullable', 'integer', 'min:0'],
            'preferred_fuel' => ['nullable', 'in:Bensin,Diesel,Hybrid,Listrik'],
        ];
    }
}
