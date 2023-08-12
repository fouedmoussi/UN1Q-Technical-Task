<?php

namespace Src\Calendar\Event\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterEventsRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'start' => 'required_with:end|date|date_format:Y-m-d H:i:s',
            'end' => 'required_with:start|date|date_format:Y-m-d H:i:s|after_or_equal:start',
        ];
    }

    public function messages(): array
    {
        return [
            'start.required_with' => 'The start date and time are required if the end date and time are provided.',
            'start.date_format' => 'The start date and time must follow the format Y-m-d H:i:s.',
            'end.required_with' => 'The end date and time are required if the start date and time are provided.',
            'end.date_format' => 'The end date and time must follow the format Y-m-d H:i:s.',
            'end.after_or_equal' => 'The end date and time must be equal to or after the start date and time.',
        ];
    }
}
