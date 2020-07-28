<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Rules extends FormRequest
{
    public function rules()
    {
        /**
         * Regra para a validação de dados
         */
        return [
            'valor_emprestimo' => 'required',
            'instituicoes' => 'array',
            'convenios' => 'array',
            'parcelas' =>  'integer'
        ];
    }
}
