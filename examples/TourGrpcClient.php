<?php

/**
 * Example: Using the Tour Service Client in Laravel
 * 
 * This example shows how to use the generated gRPC client
 * to call the Tour service from another Laravel service.
 */

namespace App\Services;

use Travel\Proto\Tour\V1\TourServiceClient;
use Travel\Proto\Tour\V1\GetTourByIdRequest;
use Travel\Proto\Tour\V1\ListToursRequest;
use Travel\Proto\Tour\V1\TourResponse;
use Travel\Proto\Tour\V1\ListToursResponse;
use Grpc\ChannelCredentials;

class TourGrpcClient
{
    private TourServiceClient $client;

    public function __construct()
    {
        // Initialize gRPC client
        // Replace with your actual service host and port
        $hostname = env('TOUR_GRPC_HOST', 'tour-service:50051');
        
        $this->client = new TourServiceClient(
            $hostname,
            [
                'credentials' => ChannelCredentials::createInsecure(),
                // For production, use SSL:
                // 'credentials' => ChannelCredentials::createSsl(),
            ]
        );
    }

    /**
     * Get a single tour by ID
     *
     * @param string $tourId
     * @param string $language
     * @return array
     * @throws \Exception
     */
    public function getTourById(string $tourId, string $language = 'en'): array
    {
        // Create request
        $request = new GetTourByIdRequest();
        $request->setTourId($tourId);
        $request->setLanguage($language);

        // Make gRPC call
        [$response, $status] = $this->client->GetTourById($request)->wait();

        // Check status
        if ($status->code !== \Grpc\STATUS_OK) {
            throw new \Exception("gRPC Error: {$status->details}", $status->code);
        }

        // Convert response to array
        return $this->tourResponseToArray($response);
    }

    /**
     * List tours with pagination
     *
     * @param int $page
     * @param int $pageSize
     * @param string|null $filter
     * @return array
     * @throws \Exception
     */
    public function listTours(int $page = 1, int $pageSize = 10, ?string $filter = null): array
    {
        // Create request
        $request = new ListToursRequest();
        $request->setPage($page);
        $request->setPageSize($pageSize);
        
        if ($filter) {
            $request->setFilter($filter);
        }

        // Make gRPC call
        [$response, $status] = $this->client->ListTours($request)->wait();

        // Check status
        if ($status->code !== \Grpc\STATUS_OK) {
            throw new \Exception("gRPC Error: {$status->details}", $status->code);
        }

        // Convert response to array
        return [
            'tours' => array_map(
                fn($tour) => $this->tourResponseToArray($tour),
                iterator_to_array($response->getTours())
            ),
            'total' => $response->getTotal(),
            'page' => $response->getPage(),
            'page_size' => $response->getPageSize(),
        ];
    }

    /**
     * Convert TourResponse to array
     *
     * @param TourResponse $response
     * @return array
     */
    private function tourResponseToArray(TourResponse $response): array
    {
        return [
            'id' => $response->getId(),
            'name' => $response->getName(),
            'description' => $response->getDescription(),
            'price' => $response->getPrice(),
            'duration_days' => $response->getDurationDays(),
            'location' => $response->getLocation(),
            'status' => $response->getStatus(),
            'created_at' => $response->getCreatedAt(),
            'updated_at' => $response->getUpdatedAt(),
        ];
    }
}
