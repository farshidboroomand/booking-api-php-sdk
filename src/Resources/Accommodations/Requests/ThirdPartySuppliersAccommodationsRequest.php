<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Requests;

use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\DTOs\ThirdPartySupplier;
use JsonException;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasStringBody;

final class ThirdPartySuppliersAccommodationsRequest extends Request implements HasBody
{
    use HasStringBody;

    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/accommodations/third-party-suppliers';
    }

    /** @return array<string, string> */
    protected function defaultHeaders(): array
    {
        return ['Content-Type' => 'application/json'];
    }

    protected function defaultBody(): string
    {
        return '{}';
    }

    /** @return list<ThirdPartySupplier> */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json('data');
        if (! is_array($data) || ! array_is_list($data)) {
            throw new JsonException('Invalid third-party suppliers response from API');
        }

        $suppliers = [];
        foreach ($data as $item) {
            if (! is_array($item) || ! is_int($item['id'] ?? null) || ! is_string($item['name'] ?? null)) {
                throw new JsonException('Invalid third-party supplier in response from API');
            }

            $suppliers[] = new ThirdPartySupplier(
                id: $item['id'],
                name: $item['name'],
                companyAddress: is_string($item['company_address'] ?? null) ? $item['company_address'] : null,
            );
        }

        return $suppliers;
    }
}
