<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Requests;

use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\AccommodationDetailsResult;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\DTOs\AccommodationDetail;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Payloads\AccommodationsDetailsPayload;
use JsonException;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class DetailsAccommodationsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(private readonly AccommodationsDetailsPayload $details) {}

    public function resolveEndpoint(): string
    {
        return '/accommodations/details';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        return $this->details->toArray();
    }

    public function createDtoFromResponse(Response $response): AccommodationDetailsResult
    {
        $data = $response->json('data');
        if (! is_array($data) || ! array_is_list($data)) {
            throw new JsonException('Invalid details response from API');
        }

        $accommodations = [];
        foreach ($data as $item) {
            if (! is_array($item) || ! is_int($item['id'] ?? null)) {
                throw new JsonException('Invalid accommodation details in response from API');
            }

            $accommodations[] = new AccommodationDetail(id: $item['id'], data: $item);
        }

        $nextPage = $response->json('metadata.next_page');

        return new AccommodationDetailsResult(
            accommodations: $accommodations,
            nextPage: is_string($nextPage) ? $nextPage : null,
        );
    }
}
