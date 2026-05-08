<?php

namespace App\Http\Requests;
use App\Models\Enclosure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
class StoreEnclosureRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:enclosures,name', 
            'limit' => 'required|integer',
            'feeding_at' => 'required|date_format:H:i', 
            'is_predator' => 'required|boolean',
        ];
    }
}
