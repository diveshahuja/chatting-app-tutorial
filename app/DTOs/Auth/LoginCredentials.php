<?php

declare(strict_types=1);

namespace App\DTOs\Auth;

use App\Http\Requests\Auth\LoginRequest;

final readonly class LoginCredentials
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember = false,
    ) {}

    /**
     * Create a DTO instance from a LoginRequest.
     */
    public static function fromRequest(LoginRequest $request): self
    {
        return new self(
            email: strtolower(trim((string) $request->validated('email'))),
            password: (string) $request->validated('password'),
            remember: (bool) $request->boolean('remember'),
        );
    }

    /**
     * @return array{email: string, password: string}
     */
    public function credentials(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
