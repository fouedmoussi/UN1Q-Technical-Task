<?php

namespace Src\Calendar\Event\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Src\Calendar\Event\Infrastructure\Rules\SameDay;

class UpdateEventRequest extends FormRequest
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
            'title' => 'required_without:start,end|string|min:10|max:255|unique:events,title,'.$this->id,
            'description' => 'nullable|string',
            'start' => 'required_without:title|required_with:end|date|date_format:Y-m-d H:i:s|after:now',
            'end' => ['required_without:title', 'required_with:start', 'date', 'date_format:Y-m-d H:i:s', 'after:start', new SameDay(\Request::input('start'))],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required_without' => "You're not required to provide all attributes when making updates. You can update either the title, a combination of start and end date times, or even all of them.",
            'title.string' => 'The title must be a string.',
            'title.min' => 'The title must be at least :min characters.',
            'title.max' => 'The title cannot exceed :max characters.',
            'title.unique' => 'The title is already used by another event.',

            'description.string' => 'The description must be a string.',

            'start.required_without' => "You're not required to provide all attributes when making updates. You can update either the title, a combination of start and end date times, or even all of them.",
            'start.required_with' => 'The start date and time are required if the end date and time are provided.',
            'start.date_format' => 'The start date and time must follow the format Y-m-d H:i:s.',
            'start.after' => 'The start date and time must be in the future.',

            'end.required_without' => "You're not required to provide all attributes when making updates. You can update either the title, a combination of start and end date times, or even all of them.",
            'end.required_with' => 'The end date and time are required if the start date and time are provided.',
            'end.date_format' => 'The end date and time must follow the format Y-m-d H:i:s.',
            'end.after' => 'The end date and time must be after the start date and time.',
        ];
    }
}
