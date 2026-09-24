<?php

namespace App\Http\Requests;

use App\Models\EquipmentType;
use App\Models\FormOption;
use App\Models\Sector;
use App\Support\Countries;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNeedRequestRequest extends FormRequest
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
            'company' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'sector_id' => ['nullable', Rule::exists(Sector::class, 'id')],
            'equipment_type_id' => ['nullable', Rule::exists(EquipmentType::class, 'id')],
            'country' => ['nullable', Rule::in(Countries::CODES)],
            'deadline_option_id' => [
                'nullable',
                Rule::exists(FormOption::class, 'id')->where('field', FormOption::NEED_DEADLINE),
            ],
            'message' => ['nullable', 'string', 'max:5000'],
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
            '*.in' => ui('form.error_invalid'),
        ];
    }
}
