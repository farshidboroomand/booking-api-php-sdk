<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Requests;

use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\AccommodationDetailsChangesResult;
use InvalidArgumentException;
use JsonException;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class DetailsChangesAccommodationsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  list<string>  $countries
     * @param  list<int>  $cities
     */
    public function __construct(
        private readonly string $lastChange,
        private readonly array $countries = [],
        private readonly array $cities = [],
    ) {
        if ($countries !== [] && $cities !== []) {
            throw new InvalidArgumentException('Filter details changes by countries or cities, not both.');
        }
    }

    public function resolveEndpoint(): string
    {
        return '/accommodations/details/changes';
    }

    /** @return array<string, mixed> */
    protected function defaultBody(): array
    {
        $body = ['last_change' => $this->lastChange];
        if ($this->countries !== []) {
            $body['filters'] = ['countries' => $this->countries];
        } elseif ($this->cities !== []) {
            $body['filters'] = ['cities' => $this->cities];
        }

        return $body;
    }

    public function createDtoFromResponse(Response $response): AccommodationDetailsChangesResult
    {
        $data = $response->json('data');
        if (! is_array($data) || ! is_array($data['changes'] ?? null) || ! is_string($data['from'] ?? null) || ! is_int($data['total_changes'] ?? null)) {
            throw new JsonException('Invalid details changes response from API');
        }

        $changes = $data['changes'];
        $closed = $changes['closed'] ?? [];
        if (! is_array($closed)) {
            throw new JsonException('Invalid closed accommodations in response from API');
        }

        return new AccommodationDetailsChangesResult(
            changed: self::ids($changes['changed'] ?? []),
            opened: self::ids($changes['opened'] ?? []),
            closed: [
                'fraud' => self::ids($closed['fraud'] ?? []),
                'permanently' => self::ids($closed['permanently'] ?? []),
                'temporarily' => self::ids($closed['temporarily'] ?? []),
            ],
            from: $data['from'],
            next: is_string($data['next'] ?? null) ? $data['next'] : null,
            totalChanges: $data['total_changes'],
        );
    }

    /** @return list<int> */
    private static function ids(mixed $value): array
    {
        if (! is_array($value) || ! array_is_list($value)) {
            throw new JsonException('Invalid accommodation IDs in details changes response');
        }
        foreach ($value as $id) {
            if (! is_int($id)) {
                throw new JsonException('Invalid accommodation ID in details changes response');
            }
        }

        return $value;
    }
}
