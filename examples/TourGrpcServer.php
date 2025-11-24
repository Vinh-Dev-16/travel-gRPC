<?php

/**
 * Example: Implementing the Tour Service Server in Laravel
 * 
 * This example shows how to implement the gRPC server
 * that handles Tour service requests.
 */

namespace App\Grpc\Services;

use Travel\Proto\Tour\V1\TourServiceInterface;
use Travel\Proto\Tour\V1\GetTourByIdRequest;
use Travel\Proto\Tour\V1\ListToursRequest;
use Travel\Proto\Tour\V1\TourResponse;
use Travel\Proto\Tour\V1\ListToursResponse;
use App\Models\Tour;
use Spiral\RoadRunner\GRPC;

class TourService implements TourServiceInterface
{
    /**
     * Get a tour by ID
     *
     * @param GRPC\ContextInterface $ctx
     * @param GetTourByIdRequest $request
     * @return TourResponse
     */
    public function GetTourById(GRPC\ContextInterface $ctx, GetTourByIdRequest $request): TourResponse
    {
        try {
            // Find tour in database
            $tour = Tour::findOrFail($request->getTourId());

            // Create response
            $response = new TourResponse();
            $response->setId($tour->id);
            $response->setName($tour->name);
            $response->setDescription($tour->description ?? '');
            $response->setPrice($tour->price);
            $response->setDurationDays($tour->duration_days);
            $response->setLocation($tour->location ?? '');
            $response->setStatus($tour->status);
            $response->setCreatedAt($tour->created_at->timestamp);
            $response->setUpdatedAt($tour->updated_at->timestamp);

            return $response;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new GRPC\Exception\GRPCException(
                "Tour not found: {$request->getTourId()}",
                GRPC\StatusCode::NOT_FOUND
            );
        } catch (\Exception $e) {
            throw new GRPC\Exception\GRPCException(
                "Internal server error: {$e->getMessage()}",
                GRPC\StatusCode::INTERNAL
            );
        }
    }

    /**
     * List tours with pagination
     *
     * @param GRPC\ContextInterface $ctx
     * @param ListToursRequest $request
     * @return ListToursResponse
     */
    public function ListTours(GRPC\ContextInterface $ctx, ListToursRequest $request): ListToursResponse
    {
        try {
            $page = max(1, $request->getPage());
            $pageSize = min(100, max(1, $request->getPageSize()));
            $filter = $request->getFilter();

            // Build query
            $query = Tour::query();

            // Apply filter if provided
            if ($filter) {
                $query->where(function ($q) use ($filter) {
                    $q->where('name', 'like', "%{$filter}%")
                        ->orWhere('location', 'like', "%{$filter}%")
                        ->orWhere('description', 'like', "%{$filter}%");
                });
            }

            // Get total count
            $total = $query->count();

            // Get paginated results
            $tours = $query
                ->skip(($page - 1) * $pageSize)
                ->take($pageSize)
                ->get();

            // Create response
            $response = new ListToursResponse();
            $response->setTotal($total);
            $response->setPage($page);
            $response->setPageSize($pageSize);

            // Add tours to response
            foreach ($tours as $tour) {
                $tourResponse = new TourResponse();
                $tourResponse->setId($tour->id);
                $tourResponse->setName($tour->name);
                $tourResponse->setDescription($tour->description ?? '');
                $tourResponse->setPrice($tour->price);
                $tourResponse->setDurationDays($tour->duration_days);
                $tourResponse->setLocation($tour->location ?? '');
                $tourResponse->setStatus($tour->status);
                $tourResponse->setCreatedAt($tour->created_at->timestamp);
                $tourResponse->setUpdatedAt($tour->updated_at->timestamp);

                $response->getTours()[] = $tourResponse;
            }

            return $response;
        } catch (\Exception $e) {
            throw new GRPC\Exception\GRPCException(
                "Internal server error: {$e->getMessage()}",
                GRPC\StatusCode::INTERNAL
            );
        }
    }
}
