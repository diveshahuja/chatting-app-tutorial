<?php

declare(strict_types=1);

namespace App\DTOs\Profile;

use App\Http\Requests\Profile\UpdateProfileRequest;

final readonly class UpdateProfileData
{
    public function __construct(
        public string $name,
        public string $email,
    ) {}

    /**
     * Create a DTO instance from an UpdateProfileRequest.
     */
    public static function fromRequest(UpdateProfileRequest $request): self
    {
        return new self(
            name: (string) $request->validated('name'),
            email: strtolower(trim((string) $request->validated('email'))),
        );
    }

    /**
     * @return array{name: string, email: string}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
