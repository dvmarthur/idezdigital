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
                if (stripos($m['name'], $municipio) !== false) {
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

    public function testShowSuccess()
    {
        // Mock do serviço IbgeService para simular a busca de municípios
        $ibgeService = $this->mock(IbgeService::class);
        $ibgeService->shouldReceive('getMunicipios')
            ->once()
            ->with('SP')
            ->andReturn([
                ['id' => 1, 'nome' => 'São Paulo'],
                ['id' => 2, 'nome' => 'São Bernardo do Campo']
            ]);

        // Request para a rota /municipios/SP/São Paulo
        $response = $this->get('/municipios/SP/São Paulo');

        // Verifica se a resposta tem status 200 OK
        $response->assertStatus(200);

        // Verifica se a resposta tem o município correto
        $response->assertJson([
            ['ibge_code' => 1, 'name' => 'São Paulo']
        ]);
    }
}
