<?php

namespace Tests\Unit;

use Faker\Factory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class MunicipiosControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function testIndex()
    {
        $estados = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];
    
        foreach ($estados as $estado) {
            $response = $this->get("/municipios/{$estado}");
            $response->assertStatus(200);
            $response->assertJsonStructure([
                '*' => ['ibge_code', 'name'],
            ]);
            $response->assertJsonMissingValidationErrors(['ibge_code', 'name']);
            $this->assertTrue(Cache::has("municipios-{$estado}"));
        }
    }

    public function testShowSuccess()
    {
        // Faz uma requisição para obter todos os municípios de um estado
        $estado = 'MG';
        $response = $this->get("/municipios/{$estado}");
        $response->assertStatus(200);
    
        // Seleciona aleatoriamente 20 municípios
        $municipios = collect(json_decode($response->getContent()));
        $selectedMunicipios = $municipios->random(20);
    
        // Faz uma requisição para obter os detalhes de cada um dos municípios selecionados
        foreach ($selectedMunicipios as $municipio) {
            $response = $this->get("/municipios/{$estado}/{$municipio->name}");
            $response->assertStatus(200);
            $response->assertJsonStructure([
                '*' => ['ibge_code', 'name'],
            ]);
        }
    }

    public function testShowNotFound()
    {

        // Request para a rota /municipios/MG/Campinas
        $response = $this->get('/municipios/MG/Campinas');

        // Verifica se a resposta tem status 404 Not Found
        $response->assertStatus(404);

        // Verifica a estrutura da resposta
        $response->assertJsonStructure([
            'error',
        ]);
    }

}
