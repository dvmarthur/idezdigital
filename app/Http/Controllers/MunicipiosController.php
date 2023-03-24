<?php

namespace App\Http\Controllers;

use App\Services\IbgeService;
use Illuminate\Http\Request;

class MunicipiosController extends Controller
{
    public function index(IbgeService $ibgeService, $estado)
    {
        $municipios = $ibgeService->getMunicipios($estado);
    
        return response()->json($municipios);
    }
}
