<?php

namespace Tests\Unit;

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
        $response = $this->get('/municipios/SP');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['ibge_code', 'name'],
        ]);
        $response->assertJsonMissingValidationErrors(['ibge_code', 'name']);
        $this->assertTrue(Cache::has('municipios-SP'));
    }

    public function testShowSuccess()
    {

        // Request para a rota /municipios/SP/São Paulo
        $response = $this->get('/municipios/SP/São Paulo');

        // Verifica se a resposta tem status 200 OK
        $response->assertStatus(200);

        // Verifica a estrutura da resposta
        $response->assertJsonStructure([
            '*' => ['ibge_code', 'name'],
        ]);

        // Verifica se a resposta contém o nome da cidade pesquisada
        $response->assertSee('São Paulo');
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
