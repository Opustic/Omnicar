<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateEquipeRequest extends FormRequest
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
            // Chauffeur
            "chauffeur_id" => "required|integer",


            "controleur_id"=>"required|integer"
            
        ];
    }


    // Retourner les erreurs en cas d'échec de validation
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator){
        throw new HttpResponseException(response()->json([
            "success"=>false,
            "failed"=>true,
            "message"=>"Erreur de validation",
            "errors_list"=> $validator->errors()
        ]));
    }





}
