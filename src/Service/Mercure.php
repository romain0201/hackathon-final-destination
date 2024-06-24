<?php

namespace App\Service\Mercure;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

class JwtProvider
{
    private Configuration $config;

    public function __construct(string $key)
    {
        $this->config = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText($key)
        );
    }

    public function __invoke(): string
    {
        $now = new \DateTimeImmutable();

        $token = $this->config->builder()
            ->issuedAt($now)
            ->withClaim('mercure', ['publish' => ['*']])
            ->getToken($this->config->signer(), $this->config->signingKey());

        return $token->toString();
    }
}
