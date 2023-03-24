<?php 

namespace App\Services;

use Illuminate\Support\Facades\Http;

class IbgeService
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'https://servicodados.ibge.gov.br/api/v1/localidades/';
    }

    public function getMunicipios($estado)
    {
        $response = Http::get($this->baseUrl . 'estados/' . $estado . '/municipios');

        if ($response->ok()) {
            return $response->json();
        }

        return null;
    }
}
