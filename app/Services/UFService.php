<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Exception;

class UFService
{
    /**
     * Obtiene el valor actual de la UF
     * 
     * @return array|null
     */
    public function getUFValue()
    {
        try {
            // Configuración común para las peticiones HTTP
            $httpConfig = [
                'timeout' => 15,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                    'Accept' => 'application/json',
                    'Accept-Language' => 'es-ES,es;q=0.9,en;q=0.8'
                ]
            ];

            // Usar solo la API de Mindicador que funciona correctamente
            $result = $this->tryMindicadorAPI($httpConfig);
            if ($result['success']) {
                return $result;
            }

            // Si Mindicador falla, intentar con Santa.cl como respaldo
            $result = $this->trySantaAPI($httpConfig);
            if ($result['success']) {
                return $result;
            }

            // Si ambas fallan, retornar error
            logger()->error('Todas las APIs de UF fallaron');
            return [
                'success' => false,
                'message' => 'No se pudo obtener el valor de la UF desde ninguna fuente'
            ];
            
        } catch (Exception $e) {
            // Log detallado del error para debugging
            logger()->error('Error al obtener valor UF: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'Error interno del sistema: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Intenta obtener el valor de la UF desde la API de Santa.cl
     */
    private function trySantaAPI($config)
    {
        try {
            $response = Http::withHeaders($config['headers'])
                           ->timeout($config['timeout'])
                           ->get('https://api.santa.cl/uf');
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['today']) && isset($data['uf'])) {
                    $ufValue = (float) $data['uf'];
                    $date = $data['today'] ?? now()->format('Y-m-d');
                    
                    return [
                        'success' => true,
                        'value' => number_format($ufValue, 2, ',', '.'),
                        'date' => $date,
                        'source' => 'API Santa.cl'
                    ];
                }
            }
            
            logger()->warning('API Santa.cl falló', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
        } catch (Exception $e) {
            logger()->error('Error con API Santa.cl: ' . $e->getMessage());
        }
        
        return ['success' => false];
    }

    /**
     * Intenta obtener el valor de la UF desde la API de Mindicador
     */
    private function tryMindicadorAPI($config)
    {
        try {
            $response = Http::withHeaders($config['headers'])
                           ->timeout($config['timeout'])
                           ->get('https://mindicador.cl/api/uf');
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['serie'][0]['valor'])) {
                    $ufValue = $data['serie'][0]['valor'];
                    
                    return [
                        'success' => true,
                        'value' => number_format($ufValue, 2, ',', '.'),
                        'date' => $data['serie'][0]['fecha'] ?? now()->format('Y-m-d'),
                        'source' => 'API Mindicador'
                    ];
                }
            }
            
            logger()->warning('API Mindicador falló', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
        } catch (Exception $e) {
            logger()->error('Error con API Mindicador: ' . $e->getMessage());
        }
        
        return ['success' => false];
    }
    
    /**
     * Obtiene información formateada de la UF
     * 
     * @return array
     */
    public function getUFInfo()
    {
        $ufData = $this->getUFValue();
        
        if (!$ufData || !$ufData['success']) {
            return [
                'success' => false,
                'message' => $ufData['message'] ?? 'No se pudo obtener el valor de la UF'
            ];
        }
        
        return [
            'success' => true,
            'value' => $ufData['value'],
            'date' => $ufData['date'],
            'source' => $ufData['source'],
            'formatted' => '$' . $ufData['value'],
            'last_update' => now()->format('H:i:s')
        ];
    }
} 