<?php

declare(strict_types=1);

namespace App\DTOs\Auth;

use App\Http\Requests\Auth\ResetPasswordRequest;

final readonly class ResetPasswordData
{
    public function __construct(
        public string $token,
        public string $email,
        public string $password,
        public string $passwordConfirmation,
    ) {}

    /**
     * Create a DTO instance from a ResetPasswordRequest.
     */
    public static function fromRequest(ResetPasswordRequest $request): self
    {
        return new self(
            token: (string) $request->validated('token'),
            email: strtolower(trim((string) $request->validated('email'))),
            password: (string) $request->validated('password'),
            passwordConfirmation: (string) $request->validated('password_confirmation'),
        );
    }

    /**
     * @return array{token: string, email: string, password: string, password_confirmation: string}
     */
    public function credentials(): array
    {
        return [
            'token' => $this->token,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->passwordConfirmation,
        ];
    }
}
