<?php

declare(strict_types=1);

use Farshidboroomand\BookingApiPhpSdk\Client;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\CarConstantsResult;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\CarSearchResult;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\Payloads\CarsSearchPayload;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\Requests\ConstantsCarsRequest;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\Requests\SearchCarsRequest;
use Saloon\Http\Faking\MockResponse;

test('it searches cars and returns pagination and search token', function (): void {
    $client = new Client(affiliateId: 1234, token: 'token');
    $client->withMockClient(mockClient([
        SearchCarsRequest::class => MockResponse::make([
            'request_id' => 'request-1',
            'data' => [['car' => ['id' => 'car-1'], 'price' => ['total' => 200]]],
            'metadata' => ['next_page' => 'cursor-2', 'total_results' => 42],
            'search_token' => 'search-token',
        ]),
    ]));

    $payload = new CarsSearchPayload(
        booker: ['country' => 'nl'],
        currency: 'EUR',
        driver: ['age' => 36],
        route: ['pickup' => ['datetime' => '2026-11-05T11:05:00', 'location' => ['airport' => 'AMS']], 'dropoff' => ['datetime' => '2026-11-10T11:05:00', 'location' => ['airport' => 'AMS']]],
        maximumResults: 10,
    );

    expect($payload->toArray())->toHaveKeys(['booker', 'currency', 'driver', 'route', 'maximum_results']);

    $result = $client->cars()->search($payload);
    expect($result)->toBeInstanceOf(CarSearchResult::class)
        ->and($result->products)->toHaveCount(1)
        ->and($result->nextPage)->toBe('cursor-2')
        ->and($result->totalResults)->toBe(42)
        ->and($result->searchToken)->toBe('search-token');
});

test('it retrieves localized car constants', function (): void {
    $client = new Client(affiliateId: 1234, token: 'token');
    $client->withMockClient(mockClient([
        ConstantsCarsRequest::class => MockResponse::make([
            'request_id' => 'request-1',
            'data' => ['car_categories' => [['id' => 'compact_suv', 'name' => ['en-gb' => 'Compact SUV']]]],
        ]),
    ]));

    $result = $client->cars()->constants(['car_categories'], ['en-gb']);

    expect($result)->toBeInstanceOf(CarConstantsResult::class)
        ->and($result->sections['car_categories'][0]['id'])->toBe('compact_suv')
        ->and((string) (new ConstantsCarsRequest)->body())->toBe('{}');
});
