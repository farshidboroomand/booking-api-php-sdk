<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Requests;

use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\AccommodationConstantsResult;
use InvalidArgumentException;
use JsonException;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasStringBody;

final class ConstantsAccommodationsRequest extends Request implements HasBody
{
    use HasStringBody;

    private const array SECTIONS = [
        'accommodation_facilities', 'accommodation_themes', 'accommodation_types',
        'bed_types', 'charge_types', 'facility_types', 'review_scores',
        'room_facilities', 'room_types',
    ];

    protected Method $method = Method::POST;

    /**
     * @param  list<string>  $constants
     * @param  list<string>  $languages
     */
    public function __construct(private readonly array $constants = [], private readonly array $languages = [])
    {
        foreach ($constants as $section) {
            if (! in_array($section, self::SECTIONS, true)) {
                throw new InvalidArgumentException("Unknown accommodation constant section: {$section}");
            }
        }
    }

    public function resolveEndpoint(): string
    {
        return '/accommodations/constants';
    }

    /** @return array<string, string> */
    protected function defaultHeaders(): array
    {
        return ['Content-Type' => 'application/json'];
    }

    protected function defaultBody(): string
    {
        $body = [];
        if ($this->constants !== []) {
            $body['constants'] = $this->constants;
        }
        if ($this->languages !== []) {
            $body['languages'] = $this->languages;
        }

        return $body === [] ? '{}' : json_encode($body, JSON_THROW_ON_ERROR);
    }

    public function createDtoFromResponse(Response $response): AccommodationConstantsResult
    {
        $data = $response->json('data');
        if (! is_array($data)) {
            throw new JsonException('Invalid constants response from API');
        }

        $sections = [];
        foreach ($data as $name => $items) {
            if (! is_string($name) || ! in_array($name, self::SECTIONS, true) || ! is_array($items) || ! array_is_list($items)) {
                throw new JsonException('Invalid constants section in response from API');
            }
            foreach ($items as $item) {
                if (! is_array($item)) {
                    throw new JsonException('Invalid constant item in response from API');
                }
            }
            $sections[$name] = $items;
        }

        return new AccommodationConstantsResult($sections);
    }
}
