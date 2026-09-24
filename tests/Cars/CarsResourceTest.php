<?php

declare(strict_types=1);

use Farshidboroomand\BookingApiPhpSdk\Client;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\CarConstantsResult;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\CarDepotScoresResult;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\CarDepotsResult;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\CarDetailsResult;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\CarSearchResult;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\Payloads\CarsSearchPayload;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\Requests\ConstantsCarsRequest;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\Requests\DepotsCarsRequest;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\Requests\DepotScoresCarsRequest;
use Farshidboroomand\BookingApiPhpSdk\Resources\Cars\Requests\DetailsCarsRequest;
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

test('it retrieves paginated car depots', function (): void {
    $client = new Client(affiliateId: 1234, token: 'token');
    $client->withMockClient(mockClient([
        DepotsCarsRequest::class => MockResponse::make([
            'request_id' => 'request-1',
            'data' => [['id' => 5944, 'city' => -2140479, 'pickup' => ['instructions' => ['en-gb' => 'Arrivals hall']]]],
            'metadata' => ['next_page' => 'cursor-2'],
        ]),
    ]));

    $result = $client->cars()->depots(languages: ['en-gb'], maximumResults: 10);

    expect($result)->toBeInstanceOf(CarDepotsResult::class)
        ->and($result->depots[0]->id)->toBe(5944)
        ->and($result->depots[0]->data['pickup']['instructions']['en-gb'])->toBe('Arrivals hall')
        ->and($result->nextPage)->toBe('cursor-2')
        ->and((string) (new DepotsCarsRequest)->body())->toBe('{}');
});

test('it retrieves depot review scores', function (): void {
    $client = new Client(affiliateId: 1234, token: 'token');
    $client->withMockClient(mockClient([
        DepotScoresCarsRequest::class => MockResponse::make([
            'request_id' => 'request-1',
            'data' => [
                ['id' => 5944, 'score' => 9.1, 'number_of_reviews' => 105, 'breakdown' => ['cleanliness' => ['score' => 8.7]]],
                ['id' => 5945, 'score' => null, 'number_of_reviews' => null, 'breakdown' => []],
            ],
            'metadata' => ['next_page' => 'cursor-2'],
        ]),
    ]));

    $result = $client->cars()->depotScores(maximumResults: 10);

    expect($result)->toBeInstanceOf(CarDepotScoresResult::class)
        ->and($result->scores[0]->score)->toBe(9.1)
        ->and($result->scores[1]->score)->toBeNull()
        ->and($result->nextPage)->toBe('cursor-2');
});

test('it retrieves paginated car specifications', function (): void {
    $client = new Client(affiliateId: 1234, token: 'token');
    $client->withMockClient(mockClient([
        DetailsCarsRequest::class => MockResponse::make([
            'request_id' => 'request-1',
            'data' => [['id' => 37715, 'make' => 'Hyundai', 'model' => 'Elantra', 'supplier' => 423]],
            'metadata' => ['next_page' => 'cursor-2'],
        ]),
    ]));

    $result = $client->cars()->details(lastModified: '2026-03-01T11:05:00+00:00', maximumResults: 10);

    expect($result)->toBeInstanceOf(CarDetailsResult::class)
        ->and($result->cars[0]->id)->toBe(37715)
        ->and($result->cars[0]->data['model'])->toBe('Elantra')
        ->and($result->nextPage)->toBe('cursor-2');
});
