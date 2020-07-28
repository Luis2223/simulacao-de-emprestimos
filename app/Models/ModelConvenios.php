<?php

namespace App\Models;

use Storage;

class ModelConvenios
{
    /**
     * Simula o select no bd
     */
    public static function Read() {
        return Storage::disk('local')->get('convenios.json');
    }
}
