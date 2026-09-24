<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Requests;

use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\DTOs\AccommodationBrand;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\DTOs\AccommodationChain;
use JsonException;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

final class ChainsAccommodationsRequest extends Request
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/accommodations/chains';
    }

    /** @return list<AccommodationChain> */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json('data');
        if (! is_array($data) || ! array_is_list($data)) {
            throw new JsonException('Invalid chains response from API');
        }

        $chains = [];
        foreach ($data as $item) {
            if (! is_array($item) || ! is_int($item['id'] ?? null) || ! is_string($item['name'] ?? null) || ! is_array($item['brands'] ?? null) || ! array_is_list($item['brands'])) {
                throw new JsonException('Invalid chain in response from API');
            }

            $brands = [];
            foreach ($item['brands'] as $brand) {
                if (! is_array($brand) || ! is_int($brand['id'] ?? null) || ! is_string($brand['name'] ?? null)) {
                    throw new JsonException('Invalid brand in chains response from API');
                }

                $brands[] = new AccommodationBrand(id: $brand['id'], name: $brand['name']);
            }

            $chains[] = new AccommodationChain(id: $item['id'], name: $item['name'], brands: $brands);
        }

        return $chains;
    }
}
