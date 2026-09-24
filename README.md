# Booking.com Demand API PHP SDK

An unofficial PHP SDK for the [Booking.com Demand API v3.2](https://developers.booking.com/demand/docs/open-api/3.2/demand-api). It currently supports the accommodation endpoints. The API may change before a stable release.

## Requirements

- PHP 8.3 or newer
- Composer 2 or newer
- Booking.com Affiliate ID and API token

## Installation

```bash
composer require farshidboroomand/booking-api-php-sdk
```

## Usage

Create a client with your credentials. The sandbox is the default environment; pass `Environment::Production` for live requests.

```php
use Farshidboroomand\BookingApiPhpSdk\Client;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Payloads\AccommodationsSearchPayload;

$client = new Client(affiliateId: 1234, token: 'your-api-token');

$result = $client->accommodations()->search(
    search: AccommodationsSearchPayload::make([
        'booker' => ['country' => 'nl', 'platform' => 'desktop'],
        'checkin' => '2026-09-01',
        'checkout' => '2026-09-10',
        'guests' => ['number_of_adults' => 2, 'number_of_rooms' => 1],
        'city' => -2140479,
    ]),
);

$accommodations = $result->accommodations;
$nextPage = $result->nextPage;
```

The accommodation resource also provides:

| Method | Purpose |
| --- | --- |
| `availability($payload)` | Live products and prices |
| `chains()` | Chains and brands |
| `constants($sections, $languages)` | Accommodation reference data |
| `details($payload)` | Property details |
| `detailsChanges($lastChange, $countries, $cities)` | Changed property IDs |
| `reviews($payload)` | Traveller reviews |
| `reviewScores($payload)` | Aggregate scores |
| `thirdPartySuppliers()` | Supplier information |

Payload classes are in `Resources\Accommodations\Payloads`. Results expose typed properties; details and review records retain their full API data for optional fields.

Car rental search is available through `$client->cars()->search($payload)`. Pass a `CarsSearchPayload` with booker, currency, driver, and route information. The result includes products, pagination, and a search token.

## Development

```bash
composer install
composer test
composer stan
composer lint
```

Tests use local mock responses and do not call Booking.com.

## License

MIT. This project is not affiliated with or endorsed by Booking.com.
