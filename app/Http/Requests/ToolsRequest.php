<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Constants\Constants; 

class ToolsRequest extends FormRequest
{
    /**
     * Determina se o user pode fazer o Request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Definição das regras para a validação do Request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:3|max:255',
            'link' => 'required|url',
            'tags' => 'sometimes|array',
            'tags.*' => 'sometimes|string'
        ];
    }


    /**
     * Tradução das mensagens de erro.
     * 
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => Lang::get('tools.validator.title.required'),
            'title.min' => Lang::get('tools.validator.title.min'),
            'title.max' => Lang::get('tools.validator.title.max'),
            
            'link.required' => Lang::get('tools.validator.link.required'),
            'link.url' => Lang::get('tools.validator.link.url'),

            'tags.array' => Lang::get('tools.validator.tags.array'),
            'tags.*' => Lang::get('tools.validator.tags.array'),
        ];
    }


    /**
     * Intercepta o response de error e customiza o output.
     * 
     * @param Validator;
     * @return \Illuminate\Http\Exceptions\HttpResponseException
     * 
     */
    protected function failedValidation($validator)
    {
        $message = Lang::get('tools.validator.message');
        $description =  $validator->errors()->first();
        $status = Constants::BAD_REQUEST;

        $response = [
            "code" => $status,
            "message" => $message,
            "description" => $description
        ];

        throw new HttpResponseException(response()->json($response, $status));
    }
}
