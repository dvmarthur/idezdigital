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
            return array_map(function ($municipio) {
                return ['ibge_code' => $municipio['id'], 'name' => $municipio['nome']];
            }, $municipios);
        });

        return response()->json($municipiosSimplificados, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function show(IbgeService $ibgeService, $estado, $municipio)
    {
        $cacheKey = 'municipios-' . $estado;

        $municipiosSimplificados = Cache::remember($cacheKey, $minutes = 60, function () use ($ibgeService, $estado) {
            $municipios = $ibgeService->getMunicipios($estado);
            return array_map(function ($municipio) {
                return ['ibge_code' => $municipio['id'], 'name' => $municipio['nome']];
            }, $municipios);
        });

        $municipioEncontrado = null;

        foreach ($municipiosSimplificados as $m) {
            if (stripos($m['name'], $municipio) !== false) {
                $municipioEncontrado[] = $m;
            }
        }

        if ($municipioEncontrado) {
            return response()->json($municipioEncontrado, 200, [], JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json(['error' => 'Município não encontrado'], 404, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
