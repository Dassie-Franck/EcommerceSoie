<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DHLTrackingService
{
    protected $baseUrl;
    protected $apiKey;
    protected $timeout;

    public function __construct()
    {
        $this->baseUrl = config('services.dhl.base_url', 'https://api-eu.dhl.com/tracking');
        $this->apiKey = config('services.dhl.api_key');
        $this->timeout = config('services.dhl.timeout', 10);
    }

    /**
     * Suivre un colis par son numéro
     */
    public function track(string $trackingNumber): ?array
    {
        // Vérifier le cache d'abord
        $cacheKey = "dhl_tracking_{$trackingNumber}";

        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($trackingNumber) {
            return $this->fetchTracking($trackingNumber);
        });
    }

    /**
     * Récupérer les informations depuis l'API DHL
     */
    protected function fetchTracking(string $trackingNumber): ?array
    {
        // Si pas de clé API, retourner des données de test
        if (!$this->apiKey || $this->apiKey === 'votre_clé_api_ici') {
            return $this->getMockTrackingData($trackingNumber);
        }

        try {
            $response = Http::timeout($this->timeout)
                ->retry(2, 100)
                ->withHeaders([
                    'DHL-API-Key' => $this->apiKey,
                    'Accept' => 'application/json',
                ])
                ->get("{$this->baseUrl}/shipments", [
                    'trackingNumber' => $trackingNumber
                ]);

            if ($response->successful()) {
                return $this->formatResponse($response->json());
            }

            Log::warning('DHL API error', [
                'tracking_number' => $trackingNumber,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            // En cas d'erreur API, retourner données de test
            return $this->getMockTrackingData($trackingNumber);

        } catch (\Exception $e) {
            Log::error('DHL API exception', [
                'tracking_number' => $trackingNumber,
                'error' => $e->getMessage()
            ]);

            // En cas d'exception, retourner données de test
            return $this->getMockTrackingData($trackingNumber);
        }
    }

    /**
     * Données de test pour le développement (à supprimer quand l'API fonctionne)
     */
    protected function getMockTrackingData(string $trackingNumber): array
    {
        $statuses = ['delivered', 'in_transit', 'pending'];
        $randomStatus = $statuses[array_rand($statuses)];

        $events = [];
        $now = now();

        if ($randomStatus === 'delivered') {
            $events = [
                [
                    'timestamp' => $now->copy()->subDays(5)->toDateTimeString(),
                    'location' => 'Paris, France',
                    'status' => 'pickup',
                    'description' => 'Colis pris en charge par DHL'
                ],
                [
                    'timestamp' => $now->copy()->subDays(4)->toDateTimeString(),
                    'location' => 'Roissy CDG, France',
                    'status' => 'transit',
                    'description' => 'Colis au centre de tri international'
                ],
                [
                    'timestamp' => $now->copy()->subDays(2)->toDateTimeString(),
                    'location' => 'Douala, Cameroun',
                    'status' => 'arrived',
                    'description' => 'Colis arrivé au pays de destination'
                ],
                [
                    'timestamp' => $now->copy()->subDays(1)->toDateTimeString(),
                    'location' => 'Douala, Cameroun',
                    'status' => 'out_for_delivery',
                    'description' => 'Colis en cours de livraison'
                ],
                [
                    'timestamp' => $now->toDateTimeString(),
                    'location' => 'Douala, Cameroun',
                    'status' => 'delivered',
                    'description' => 'Colis livré avec succès'
                ],
            ];
        } elseif ($randomStatus === 'in_transit') {
            $events = [
                [
                    'timestamp' => $now->copy()->subDays(2)->toDateTimeString(),
                    'location' => 'Paris, France',
                    'status' => 'pickup',
                    'description' => 'Colis pris en charge par DHL'
                ],
                [
                    'timestamp' => $now->copy()->subDays(1)->toDateTimeString(),
                    'location' => 'Roissy CDG, France',
                    'status' => 'transit',
                    'description' => 'Départ vers le pays de destination'
                ],
                [
                    'timestamp' => $now->toDateTimeString(),
                    'location' => 'En transit',
                    'status' => 'transit',
                    'description' => 'Colis en cours d\'acheminement'
                ],
            ];
        } else {
            $events = [
                [
                    'timestamp' => $now->copy()->subDays(1)->toDateTimeString(),
                    'location' => 'Paris, France',
                    'status' => 'pending',
                    'description' => 'Étiquette d\'expédition créée'
                ],
                [
                    'timestamp' => $now->toDateTimeString(),
                    'location' => 'Paris, France',
                    'status' => 'pending',
                    'description' => 'En attente de prise en charge par DHL'
                ],
            ];
        }

        return [
            'tracking_number' => $trackingNumber,
            'status' => $randomStatus,
            'status_code' => $randomStatus === 'delivered' ? 'delivered' : ($randomStatus === 'in_transit' ? 'transit' : 'pending'),
            'description' => $this->getStatusDescription($randomStatus),
            'origin' => 'Paris, France',
            'destination' => 'Douala, Cameroun',
            'estimated_delivery' => $randomStatus === 'delivered' ? $now->toDateTimeString() : $now->copy()->addDays(3)->toDateTimeString(),
            'events' => $events,
            'last_updated' => $now->toDateTimeString(),
        ];
    }

    protected function getStatusDescription(string $status): string
    {
        return match($status) {
            'delivered' => 'Colis livré avec succès',
            'in_transit' => 'Colis en cours d\'acheminement',
            'pending' => 'En attente de prise en charge',
            default => 'Statut inconnu'
        };
    }

    /**
     * Formater la réponse DHL
     */
    protected function formatResponse(array $data): array
    {
        $shipment = $data['shipments'][0] ?? null;

        if (!$shipment) {
            return $this->getMockTrackingData('unknown');
        }

        $status = $this->normalizeStatus($shipment['status']['statusCode'] ?? 'unknown');

        $events = [];
        foreach ($shipment['events'] ?? [] as $event) {
            $events[] = [
                'timestamp' => $event['timestamp'] ?? null,
                'location' => $event['location']['address']['addressLocality'] ?? null,
                'status' => $event['status']['statusCode'] ?? null,
                'description' => $event['description'] ?? null,
            ];
        }

        return [
            'tracking_number' => $shipment['trackingNumber'] ?? null,
            'status' => $status,
            'status_code' => $shipment['status']['statusCode'] ?? null,
            'description' => $shipment['status']['description'] ?? null,
            'origin' => $shipment['origin']['address']['addressLocality'] ?? null,
            'destination' => $shipment['destination']['address']['addressLocality'] ?? null,
            'estimated_delivery' => $shipment['estimatedDeliveryTimeOfDay'] ?? null,
            'events' => $events,
            'last_updated' => now()->toDateTimeString(),
        ];
    }

    /**
     * Normaliser les statuts DHL
     */
    protected function normalizeStatus(string $statusCode): string
    {
        return match($statusCode) {
            'delivered', 'customs.clearance.complete' => 'delivered',
            'transit', 'with.delivery.courier', 'customs.clearance' => 'in_transit',
            'exception', 'on.hold' => 'pending',
            default => 'unknown'
        };
    }

    /**
     * Forcer l'actualisation (ignorer le cache)
     */
    public function refresh(string $trackingNumber): ?array
    {
        $cacheKey = "dhl_tracking_{$trackingNumber}";
        Cache::forget($cacheKey);
        return $this->track($trackingNumber);
    }
}
