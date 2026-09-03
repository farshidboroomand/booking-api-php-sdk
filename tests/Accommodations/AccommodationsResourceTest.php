<?php

declare(strict_types=1);

use Farshidboroomand\BookingApiPhpSdk\Client;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\AccommodationSearchResult;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\DTOs\Accommodation;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Payloads\AccommodationsSearchPayload;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Requests\SearchAccommodationsRequest;

describe('Accommodations Search', function (): void {
    test('it can search accommodations', function (): void {
        $connector = new Client(
            affiliateId: 1234,
            token: 'ozzy osbourne token'
        );

        $connector->withMockClient(mockClient([
            SearchAccommodationsRequest::class => fakeResponse('accommodations/search'),
        ]));

        $response = $connector->accommodations()->search(
            search: AccommodationsSearchPayload::make([
                'booker' => ['country' => 'us', 'platform' => 'desktop'],
                'checkin' => '2026-10-01',
                'checkout' => '2026-10-05',
                'guests' => ['number_of_adults' => 2, 'number_of_rooms' => 1],
                'country' => 'fr',
            ]),
        );

        expect($response)->toBeInstanceOf(AccommodationSearchResult::class);
        expect($response->accommodations)->toBeArray()->each->toBeInstanceOf(Accommodation::class);
        expect($response->nextPage)->toBeString();
    });
});
