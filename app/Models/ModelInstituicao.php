<?php

namespace App\Models;

use Storage;

class ModelInstituicao
{
    /**
     * Simula o select no bd
     */
    public static function Read() {
        return Storage::disk('local')->get('instituicoes.json');
    }
}
