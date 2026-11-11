<?php

declare(strict_types=1);

namespace App\DTOs\Auth;

use App\Http\Requests\Auth\RegisterRequest;

final readonly class RegisterUserData
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {}

    /**
     * Create a DTO instance from a RegisterRequest.
     */
    public static function fromRequest(RegisterRequest $request): self
    {
        return new self(
            name: (string) $request->validated('name'),
            email: strtolower(trim((string) $request->validated('email'))),
            password: (string) $request->validated('password'),
        );
    }

    /**
     * @return array{name: string, email: string, password: string}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
