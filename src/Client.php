<?php

declare(strict_types=1);

namespace Farshidboroomand\BookingApiPhpSdk;

use Farshidboroomand\BookingApiPhpSdk\Enums\Environment;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Http\PendingRequest;
use SensitiveParameter;

final class Client extends Connector
{
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
}
