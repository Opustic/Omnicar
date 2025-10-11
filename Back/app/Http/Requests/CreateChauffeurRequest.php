<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateChauffeurRequest extends FormRequest
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
            'nom_chauffeur' => 'required|string|max:255',
            'telephone_chauffeur' => 'required|string|max:20',
            'adresse_chauffeur' => 'required|string|max:255',
            'email_chauffeur' => 'nullable|email|max:255',
            'numero_permis_chauffeur' => 'nullable|string|max:255',
            'photo_chauffeur' => 'required|image|max:2048',
        ];
    }


    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator){
        throw new HttpResponseException(response()->json([
            "success"=>false,
            "failed"=>true,
            "errors"=>$validator->errors()
        ]));
    }


}
