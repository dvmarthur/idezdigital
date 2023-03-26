<?php 

namespace App\Services;

use Illuminate\Support\Facades\Http;

class IbgeService
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('IBGE_API');
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
