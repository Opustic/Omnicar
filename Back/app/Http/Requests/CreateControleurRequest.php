<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateControleurRequest extends FormRequest
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
            // Contrôleur
            'nom_controleur' => 'required|string|max:255',
            'telephone_controleur' => 'required|string|max:20',
            'adresse_controleur' => 'required|string|max:255',
            'email_controleur' => 'nullable|email|max:255',
            'photo_controleur' => 'required|image|max:2048'
        ];
    }


    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator){
        throw new HttpResponseException(response()->json([
            "success"=>false,
            "failed"=>true,
            "errors_list"=>$validator->errors()
        ]));
    }

}
