<?php

namespace App\Http\Requests;

use App\Models\FormOption;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactMessageRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject_option_id' => [
                'nullable',
                Rule::exists(FormOption::class, 'id')->where('field', FormOption::CONTACT_SUBJECT),
            ],
            'message' => ['required', 'string', 'max:5000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            '*.required' => ui('form.error_required'),
            '*.email' => ui('form.error_email'),
            '*.max' => ui('form.error_max'),
            '*.exists' => ui('form.error_invalid'),
        ];
    }
}
