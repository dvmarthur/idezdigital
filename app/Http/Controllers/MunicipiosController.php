<?php

namespace App\Http\Controllers;

use App\Services\IbgeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MunicipiosController extends Controller
{
    public function index(IbgeService $ibgeService, $estado)
    {
        try {
            $cacheKey = 'municipios-' . $estado;

            $municipiosSimplificados = Cache::remember($cacheKey, $minutes = 60, function () use ($ibgeService, $estado) {
                $municipios = $ibgeService->getMunicipios($estado);
                return array_map(function ($municipio) {
                    return ['ibge_code' => $municipio['id'], 'name' => $municipio['nome']];
                }, $municipios);
            });

            return response()->json($municipiosSimplificados, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocorreu um erro ao buscar os municípios.'], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    public function show(IbgeService $ibgeService, $estado, $municipio)
    {

        try {
            $cacheKey = 'municipios-' . $estado;

            $municipiosSimplificados = Cache::remember($cacheKey, $minutes = 60, function () use ($ibgeService, $estado) {
                $municipios = $ibgeService->getMunicipios($estado);
                return array_map(function ($municipio) {
                    return ['ibge_code' => $municipio['id'], 'name' => $municipio['nome']];
                }, $municipios);
            });

            $municipioEncontrado = null;

            foreach ($municipiosSimplificados as $m) {
                $municipioSemAcento = iconv('UTF-8', 'ASCII//TRANSLIT', $municipio);
                $nomeMunicipioSemAcento = iconv('UTF-8', 'ASCII//TRANSLIT', $m['name']);
            
                if (stripos($nomeMunicipioSemAcento, $municipioSemAcento) !== false || stripos($municipioSemAcento, $nomeMunicipioSemAcento) !== false) {
                    $municipioEncontrado[] = $m;
                }
            }

            if ($municipioEncontrado) {
                return response()->json($municipioEncontrado, 200, [], JSON_UNESCAPED_UNICODE);
            } else {
                return response()->json(['error' => 'Município não encontrado'], 404, [], JSON_UNESCAPED_UNICODE);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    
}
