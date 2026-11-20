<?php

namespace App\Security;

use App\Entity\Token;
use App\Entity\User;
use App\Repository\TokenRepository;

class TokenService
{
    public function __construct(
        private TokenRepository $tokenRepository,
    )
    {

    }

    public function generateTokenValue(): string
    {
        return bin2hex(random_bytes(32));
    }

    public function createToken(User $user): Token
    {
        return new Token()
            ->setUser($user)
            ->setValue($this->generateTokenValue())
            ->setExpireAt(new \DateTimeImmutable()->add(new \DateInterval('PT15M')));
    }

    public function getTokenByValue(string $value): ?Token
    {
        return $this->tokenRepository->findValidToken($value);
    }
}
