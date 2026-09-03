<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk;

use Farshidboroomand\BookingApiPhpSdk\Enums\Environment;
use Farshidboroomand\BookingApiPhpSdk\Resources\Accommodations\AccommodationsResource;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Http\PendingRequest;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use SensitiveParameter;

final class Client extends Connector
{
    use AlwaysThrowOnErrors;

    public const string VERSION = '1.0.0';

    public function __construct(
        #[SensitiveParameter]
        private readonly int $affiliateId,
        #[SensitiveParameter]
        private readonly string $token,
        private readonly Environment $environment = Environment::Sandbox
    ) {}

    public function boot(PendingRequest $pendingRequest): void
    {
        $pendingRequest->headers()->add(
            key: 'X-Affiliate-Id',
            value: (string) $this->affiliateId,
        );
    }

    public function resolveBaseUrl(): string
    {
        return $this->environment->baseUrl();
    }

    protected function defaultAuth(): TokenAuthenticator
    {
        return new TokenAuthenticator(
            token: $this->token
        );
    }

    public function accommodations(): AccommodationsResource
    {
        return new AccommodationsResource(connector: $this);
    }
}
