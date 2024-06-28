<?php

namespace App\Http\Requests;

use App\Rules\AlphaSpace;
use App\Rules\Phone;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
            'name' => ['required', new AlphaSpace, 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'support_email' => ['nullable', 'email', 'max:255'],
            'admin_email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'max:255', new Phone],
            'mobile' => ['nullable', 'max:255', new Phone],
            'whatsapp' => ['nullable', 'max:255', new Phone],
            'address' => ['nullable', 'string', 'max:255'],
            'copyright_text' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'logo' => [...$this->isPrecognitive() ? [] : ['nullable'], 'image', 'max:5120'],
            'favicon' => [...$this->isPrecognitive() ? [] : ['nullable'], 'image', 'max:5120'],
            'alternate_logo' => [...$this->isPrecognitive() ? [] : ['nullable'], 'image', 'max:5120'],
            'portal_logo' => [...$this->isPrecognitive() ? [] : ['nullable'], 'image', 'max:5120'],
            'primary_color' => ['required', 'string', 'starts_with:#', 'min:7', 'max:7'],
            'secondary_color' => ['required', 'string', 'starts_with:#', 'min:7', 'max:7'],
            'accent_color' => ['required', 'string', 'starts_with:#', 'min:7', 'max:7'],
            'background_color' => ['required', 'string', 'starts_with:#', 'min:7', 'max:7'],
            'table_stripe_color' => ['required', 'string', 'starts_with:#', 'min:7', 'max:7'],
            'under_maintenance' => ['boolean'],
        ];
    }
}
