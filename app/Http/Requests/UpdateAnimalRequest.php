<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helper\Helper; // Import the Helper class
use \App\Models\Enclosure;
class UpdateAnimalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->admin === true;
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
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'enclosure_id' => 'required|exists:enclosures,id',
        ];
    }
    public function withValidator($validator)
{
    $validator->after(function ($validator) {
        $species = $this->input('species');
        if ($species !== null && $species !== '') {
            $isPredator = Helper::isPredator($species);
    
            
            if ($isPredator && !$this->input('is_predator')) {
                $validator->errors()->add('is_predator', 'The selected species is a predator, so "is_predator" must be true.');
            } elseif (!$isPredator && $this->input('is_predator')) {
                $validator->errors()->add('is_predator', 'The selected species is not a predator, so "is_predator" must be false.');
            }
        }

        
        $enclosure = Enclosure::find($this->input('enclosure_id'));
        if ($enclosure && $enclosure->is_predator != $this->input('is_predator')) {
            $validator->errors()->add('enclosure_id', 'The selected enclosure does not match the predator status of the animal.');
        }
        if ($enclosure && $enclosure->animals->count() > $enclosure->limit) {
            $validator->errors()->add('enclosure_id', 'The selected enclosure has reached its animal limit.');
        }
    });
}
}
