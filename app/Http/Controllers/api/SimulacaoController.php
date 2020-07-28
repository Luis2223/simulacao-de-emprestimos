<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Rules;
use App\Models\ModelSimulacao;
use Storage;

class SimulacaoController extends Controller
{
    public function store(Rules $request)
    {

        $conteudo = Storage::disk('local')->get('taxas_instituicoes.json');
        $conteudo = json_decode($conteudo, true);

        $saida = [];
        foreach($conteudo as $key => $valor) {
            switch ($valor['instituicao']) {
                /**
                 * Formata e agrupa todas as simulações recebidas no arquivo
                 */
                case 'PAN':
                    if(!isset($saida['PAN'])) {
                        $saida['PAN'] = [];
                    }

                    $valor['valor_parcela'] = number_format(($request->input('valor_emprestimo') * $valor['coeficiente']), 2, '.', ',');
                    unset($valor['taxajuros']);
                    unset($valor['coeficiente']);
                    unset($valor['instituicao']);

                    array_push($saida['PAN'], $valor);

                    break;

                case 'OLE':
                    if(!isset($saida['OLE'])) {
                        $saida['OLE'] = [];
                    }

                    $valor['valor_parcela'] = number_format(($request->input('valor_emprestimo') * $valor['coeficiente']), 2, '.', ',');
                    unset($valor['taxajuros']);
                    unset($valor['coeficiente']);
                    unset($valor['instituicao']);

                    array_push($saida['OLE'], $valor);
                    break;

                case 'BMG':
                    if(!isset($saida['BMG'])) {
                        $saida['BMG'] = [];
                    }

                    $valor['valor_parcela'] = number_format(($request->input('valor_emprestimo') * $valor['coeficiente']), 2, '.', ',');
                    unset($valor['taxajuros']);
                    unset($valor['coeficiente']);
                    unset($valor['instituicao']);

                    array_push($saida['BMG'], $valor);

                    break;
            }
        }

        /**
         * Verifica se existe um filtro por instituições
         */

        if($request->input('instituicoes')) {
            $instituicoes = $request->input('instituicoes');

            $saida = ModelSimulacao::Read($instituicoes, $saida);
        }

        /**
         * Verifica se existe um filtro por convenios
         */

        if($request->input('convenios')) {
            $convenios = $request->input('convenios');

            $array = [];
            foreach($saida as $key => $valor) {
                $array[$key] = ModelSimulacao::ExistsConvenio($convenios, $valor);
            }

            $saida = $array;
        }


        if($request->input('parcelas')) {
            $parcelas = $request->input('parcelas');

            foreach($saida as $key => $valor) {
                /**
                 * Verifica se existe alguma instiuição que fornece a quantidade de parcelas solicitadas
                 */
                if(!empty(ModelSimulacao::ExistsParcelas($parcelas, $valor))) {
                    $simulacao[$key] = ModelSimulacao::ExistsParcelas($parcelas, $valor);
                }
            }

            /**
             * Caso não exista, cria uma simulação ficticia calculando o coeficiente de 4%
             */
            if(!isset($simulacao)) {
                $taxajuros = 0.04;
                $coeficiente = $taxajuros / (1 - (1 / pow((1 + $taxajuros), $parcelas)));

                return [
                    "parcelas" => $parcelas,
                    "taxaJuros" => $taxajuros,
                    "convenio" => "INSS",
                    "valor_parcela" =>  number_format(($request->input('valor_emprestimo') * $coeficiente), 2, '.', ',')
                ];
            }

            $saida = $simulacao;

        }

        return $saida;
    }

}
