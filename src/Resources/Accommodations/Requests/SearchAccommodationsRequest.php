<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Requests;

use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\DTOs\Accommodation;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Payloads\AccommodationsSearchPayload;
use JsonException;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class SearchAccommodationsRequest extends Request implements HasBody
{
    use HasJsonBody;

    public function __construct(
        private readonly AccommodationsSearchPayload $search
    ) {}

    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/accommodations/search';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->search->toArray();
    }

    /**
     * @return array<int, Accommodation>
     *
     * @throws JsonException
     */
    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();
        if (! isset($data['data']) || ! is_array($data['data'])) {
            throw new JsonException('Invalid response from API');
        }

        /** @var list<array<string, mixed>> $items */
        $items = $data['data'];
        $result = [];

        foreach ($items as $item) {
            /** @var array<string, mixed> $currency */
            $currency = is_array($item['currency'] ?? null) ? $item['currency'] : [];

            /** @var array<string, mixed>|null $commission */
            $commission = is_array($item['commission'] ?? null) ? $item['commission'] : null;

            /** @var array<string, mixed>|null $price */
            $price = is_array($item['price'] ?? null) ? $item['price'] : null;

            /** @var array<string, mixed>|null $products */
            $products = is_array($item['products'] ?? null) ? $item['products'] : null;

            $result[] = new Accommodation(
                id: is_int($item['id'] ?? null) ? $item['id'] : 0,
                currency: $currency,
                deep_link_url: is_string($item['deep_link_url'] ?? null) ? $item['deep_link_url'] : null,
                url: is_string($item['url'] ?? null) ? $item['url'] : null,
                commission: $commission,
                price: $price,
                products: $products,
            );
        }

        return $result;
    }
}
