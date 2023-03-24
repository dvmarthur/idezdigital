<?php

namespace App\Http\Controllers;

use App\Services\IbgeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MunicipiosController extends Controller
{
    public function index(IbgeService $ibgeService, $estado)
    {
        $cacheKey = 'municipios-' . $estado;

        $municipiosSimplificados = Cache::remember($cacheKey, $minutes = 60, function () use ($ibgeService, $estado) {
            $municipios = $ibgeService->getMunicipios($estado);
            return array_map(function($municipio) {
                return ['id' => $municipio['id'], 'nome' => $municipio['nome']];
            }, $municipios);
        });
        
        return response()->json($municipiosSimplificados);
    }
}
