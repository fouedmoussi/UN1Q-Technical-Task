<?php

namespace Src\Calendar\Event\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Src\Calendar\Event\Infrastructure\Rules\SameDay;

class CreateEventRequest extends FormRequest
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
            'title' => 'required|string|min:10|max:255|unique:events',
            'description' => 'nullable|string',
            'start' => 'required|date|date_format:Y-m-d H:i:s|after:now',
            'end' => ['required', 'date', 'date_format:Y-m-d H:i:s', 'after:start', new SameDay(\Request::input('start'))],
            'recurring_frequency' => 'nullable|in:daily,weekly,monthly,yearly',
            'recurring_ends_at' => 'nullable|required_with:recurring_frequency|date|date_format:Y-m-d H:i:s|after:end',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title is required.',
            'title.string' => 'The title must be a string.',
            'title.min' => 'The title must be at least :min characters.',
            'title.max' => 'The title must not exceed 255 characters.',
            'title.unique' => 'The title is already used by another event.',
            'description.string' => 'The description must be a string.',
            'start.required' => 'The start date and time are required.',
            'start.date_format' => 'The start date and time must follow the format Y-m-d H:i:s.',
            'start.after' => 'The start date and time must be in the future.',
            'end.required' => 'The end date and time are required.',
            'end.date_format' => 'The end date and time must follow the format Y-m-d H:i:s.',
            'end.after' => 'The end date and time must be after the start date and time.',
            'recurring_frequency.in' => 'The recurring frequency must be one of daily, weekly, monthly, or yearly.',
            'recurring_ends_at.required_with' => 'If a recurring frequency is specified, a recurring end date is required.',
            'recurring_ends_at.date' => 'The recurring end date and time format is invalid.',
            'recurring_ends_at.date_format' => 'The recurring end date must follow the format Y-m-d H:i:s.',
            'recurring_ends_at.after' => 'The recurring end date must be after the event end date.',
        ];
    }
}
