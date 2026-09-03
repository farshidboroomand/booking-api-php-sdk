# Booking.com Demand API PHP SDK

An unofficial PHP SDK for the [Booking.com Demand API](https://developers.booking.com/demand/docs/open-api/3.2/demand-api) (version 3.2). It wraps the API behind typed, readable PHP objects so you can search and later book inventory without hand-writing the HTTP calls and payloads.

This package is built on top of the [Saloon](https://github.com/saloonphp/saloon) HTTP client and follows the structure popularised by the [Sevalla SDK](https://github.com/JustSteveKing/sevalla-sdk), adapted for Booking.com's **POST-based**, payload-driven API.

> **Status: under construction.** This SDK is an early-stage project. Right now it supports the accommodation search endpoint, and new resources are being added. The public API and namespaces may change before a stable `1.0.0` release. Use it for exploration and testing, not yet for production traffic.

---

## Requirements

- PHP 8.3 or newer
- Composer 2 or newer
- A Booking.com Affiliate Partner account (for a token and affiliate ID) to call the live API

## Installation

Install it with Composer:

```bash
composer require farshidboroomand/booking-api-php-sdk
```

If you are developing locally instead of pulling from Packagist, add it as a VCS (or path) repository in your own `composer.json` and install from there.

## Getting started

### 1. Create a client

The `Client` is the single entry point. It holds your credentials and lets you choose which environment to talk to. It defaults to the Booking.com sandbox, which is safe for testing.

```php
<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Farshidboroomand\BookingApiPhpSdk\Client;

$client = new Client(
    affiliateId: 1234,          // your Affiliate Partner ID (integer)
    token: 'your-api-token',    // your token (no "Bearer" prefix)
);
```

To point the same client at production once you are ready, pass the environment:

```php
$client = new Client(
    affiliateId: 1234,
    token: 'your-api-token',
    environment: Environment::Production,
);
```

Valid environments are `Environment::Sandbox` (the default) and `Environment::Production`.

### 2. Search accommodations

Every endpoint accepts a typed payload. Build it with the `make()` factory, then call the matching method on the resource.

```php
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\Payloads\AccommodationsSearchPayload;

$result = $client->accommodations()->search(
    search: AccommodationsSearchPayload::make([
        'booker'  => ['country' => 'nl', 'platform' => 'desktop'],
        'checkin' => '2026-09-01',
        'checkout' => '2026-09-10',
        'guests'  => ['number_of_adults' => 2, 'number_of_rooms' => 1],
        'city'    => -2140479,
    ]),
);
```

### 3. Work with the results

The call returns an `AccommodationSearchResult` containing the typed accommodations and, when present, the cursor for the next page.

```php
foreach ($result->accommodations as $accommodation) {
    printf("#%d %s\n", $accommodation->id, $accommodation->url);
}

if ($result->nextPage !== null) {
    // pass $result->nextPage back into the payload's 'page' field to fetch the next page
}
```

### Error handling

Network failures and non-2xx responses from the API throw exceptions from the Saloon library, so you can catch them and read the detail Booking.com returned.

```php
use Saloon\Exceptions\Request\RequestException;

try {
    $result = $client->accommodations()->search($search);
} catch (RequestException $e) {
    $errors = $e->getResponse()->json('errors');
}
```

---

## Project structure

The folder layout mirrors the API groups and each layer's role.

```
src/
├── Client.php                  entry point (credentials, environment, headers)
├── Enums/                      fixed string values as typed enums
└── Resources/
    └── Accommodations/
        ├── AccommodationsResource.php      one method per endpoint
        ├── AccommodationSearchResult.php   typed result (accommodations + next page)
        ├── DTOs/Accommodation.php          one item from the response
        ├── Payloads/                      request bodies
        └── Requests/                      one HTTP call per file
```

## Development

Clone the repository and install the development dependencies:

```bash
composer install
```

Run the checks:

```bash
composer test     # Pest test suite
composer stan     # PHPStan static analysis (level 10)
composer lint     # Pint code style check (no changes)
```

Fix code style automatically with:

```bash
composer pint
```

The tests run against local fixtures, so they never touch the real API.

## Scope and roadmap

The first resource, accommodation search, is in place. The remaining endpoints of the Demand API are planned and will be added resource by resource: the rest of the accommodations operations, car rentals, common lookups (locations, payments, languages), and eventually orders.

## License

MIT. This project is not affiliated with or endorsed by Booking.com.