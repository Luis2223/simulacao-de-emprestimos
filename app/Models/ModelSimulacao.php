<?php

namespace App\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ModelSimulacao
{
    public static function Read($values, $saida) {
        $array = [];
        foreach ($values as $key => $value) {
            $array[$value] = Arr::pull($saida, $value);
        }

        return $array;
    }

    public static function ExistsConvenio($convenios, $saida) {
        $array = [];
        foreach ($convenios as $key => $convenio) {
            foreach($saida as $key => $valor) {
                if($valor['convenio'] == $convenio) {
                    $array[] = $valor;
                }
            }
        }

        return $array;
    }


    public static function ExistsParcelas($parcelas, $saida) {
        $array = [];
        foreach($saida as $key => $valor) {
            if($valor['parcelas'] == $parcelas) {
                $array[] = $valor;
            }
        }

        return $array;
    }

}
