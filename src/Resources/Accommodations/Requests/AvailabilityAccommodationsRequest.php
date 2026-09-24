<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Requests;

use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\DTOs\AccommodationAvailability;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Payloads\AccommodationsAvailabilityPayload;
use JsonException;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class AvailabilityAccommodationsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(private readonly AccommodationsAvailabilityPayload $availability) {}

    public function resolveEndpoint(): string
    {
        return '/accommodations/availability';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return $this->availability->toArray();
    }

    /** @return list<AccommodationAvailability> */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json('data');
        if (! is_array($data) || ! array_is_list($data)) {
            throw new JsonException('Invalid availability response from API');
        }

        $result = [];
        foreach ($data as $item) {
            if (! is_array($item) || ! is_int($item['id'] ?? null) || ! is_array($item['products'] ?? null)) {
                throw new JsonException('Invalid accommodation in availability response');
            }

            $result[] = new AccommodationAvailability(
                id: $item['id'],
                currency: is_array($item['currency'] ?? null) ? $item['currency'] : [],
                products: $item['products'],
                recommendation: is_array($item['recommendation'] ?? null) ? $item['recommendation'] : null,
                deep_link_url: is_string($item['deep_link_url'] ?? null) ? $item['deep_link_url'] : null,
                url: is_string($item['url'] ?? null) ? $item['url'] : null,
            );
        }

        return $result;
    }
}
