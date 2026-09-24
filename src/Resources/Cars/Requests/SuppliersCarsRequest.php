<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Cars\Requests;

use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\CarSuppliersResult;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\DTOs\CarSupplier;
use InvalidArgumentException;
use JsonException;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasStringBody;

final class SuppliersCarsRequest extends Request implements HasBody
{
    use HasStringBody;

    protected Method $method = Method::POST;

    /** @param list<int> $suppliers */
    public function __construct(private readonly array $suppliers = [], private readonly ?int $maximumResults = null, private readonly ?string $page = null)
    {
        if (count($suppliers) > 100 || array_filter($suppliers, fn (int $id): bool => $id < 1)) {
            throw new InvalidArgumentException('Suppliers accepts at most 100 positive supplier IDs.');
        }
        if ($maximumResults !== null && ($maximumResults < 10 || $maximumResults > 100 || $maximumResults % 10 !== 0)) {
            throw new InvalidArgumentException('Suppliers maximum_results must be a multiple of 10 between 10 and 100.');
        }
    }

    public function resolveEndpoint(): string
    {
        return '/cars/suppliers';
    }

    /** @return array<string, string> */
    protected function defaultHeaders(): array
    {
        return ['Content-Type' => 'application/json'];
    }

    protected function defaultBody(): string
    {
        $body = [];
        if ($this->suppliers !== []) {
            $body['suppliers'] = $this->suppliers;
        }
        if ($this->maximumResults !== null) {
            $body['maximum_results'] = $this->maximumResults;
        }
        if ($this->page !== null) {
            $body['page'] = $this->page;
        }

        return $body === [] ? '{}' : json_encode($body, JSON_THROW_ON_ERROR);
    }

    public function createDtoFromResponse(Response $response): CarSuppliersResult
    {
        $data = $response->json('data');
        if (! is_array($data) || ! array_is_list($data)) {
            throw new JsonException('Invalid car suppliers response from API');
        }

        $suppliers = [];
        foreach ($data as $item) {
            if (! is_array($item) || ! is_int($item['id'] ?? null) || ! is_string($item['name'] ?? null)) {
                throw new JsonException('Invalid car supplier in response');
            }
            $suppliers[] = new CarSupplier(
                id: $item['id'],
                name: $item['name'],
                logo: is_string($item['logo'] ?? null) ? $item['logo'] : null,
            );
        }

        $nextPage = $response->json('metadata.next_page');
        $totalResults = $response->json('metadata.total_results');

        return new CarSuppliersResult($suppliers, is_string($nextPage) ? $nextPage : null, is_int($totalResults) ? $totalResults : null);
    }
}
