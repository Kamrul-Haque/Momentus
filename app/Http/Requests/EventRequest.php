<?php

namespace App\Http\Requests;

use App\Rules\AlphaSpace;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', new AlphaSpace, 'max:255'],
            'description' => ['nullable', 'string'],
            'start_time' => ['required'],
            'start_date' => ['required', 'date'],
            'end_time' => ['nullable'],
            'end_date' => ['nullable', 'date'],
            'users' => ['nullable', 'array']
        ];
    }
}
