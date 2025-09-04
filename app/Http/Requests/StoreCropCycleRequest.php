<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCropCycleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'farm_id' => 'required|exists:farms,id',
            'crop_type' => 'required|string|max:255',
            'variety' => 'nullable|string|max:255',
            'season_label' => 'nullable|string|max:100',
            'date_planted' => 'required|date',
            'date_harvested' => 'nullable|date|after_or_equal:date_planted',
            'yield_kg' => 'nullable|numeric|min:0',
        ];
    }
}
