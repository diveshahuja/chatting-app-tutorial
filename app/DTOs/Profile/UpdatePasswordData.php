<?php

declare(strict_types=1);

namespace App\DTOs\Profile;

use App\Http\Requests\Profile\UpdatePasswordRequest;

final readonly class UpdatePasswordData
{
    public function __construct(
        public string $currentPassword,
        public string $newPassword,
    ) {}

    /**
     * Create a DTO instance from an UpdatePasswordRequest.
     */
    public static function fromRequest(UpdatePasswordRequest $request): self
    {
        return new self(
            currentPassword: (string) $request->validated('current_password'),
            newPassword: (string) $request->validated('password'),
        );
    }
}
