<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ModelConvenios;

class ConveniosController extends Controller
{
    public function index()
    {
        return ModelConvenios::Read();
    }
}
