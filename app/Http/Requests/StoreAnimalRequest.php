<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnimalRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'name' => 'required|string|min:3|max:255',
            'species' => 'required|string|max:255',
            'is_predator' => 'required|boolean',
            'born_at' => 'required|date',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'enclosure_id' => 'required|exists:enclosures,id',
        ];
    }

    public function messages(){
        return [
            'name.required' => 'The name field is required.',
            'species.required' => 'The species field is required.',
            'is_predator.required' => 'The is predator field is required.',
            'born_at.required' => 'The born at field is required.',
            'picture.image' => 'The picture must be an image.',
            'enclosure_id.required' => 'The enclosure id field is required.',
        ];
    }

    
public function withValidator($validator)
{
    $validator->after(function ($validator) {
        $enclosure = \App\Models\Enclosure::find($this->input('enclosure_id'));

        if ($enclosure && $enclosure->animals->count() >= $enclosure->limit) {
            $validator->errors()->add('enclosure_id', 'The selected enclosure has reached its animal limit.');
        }
    });
}
}
