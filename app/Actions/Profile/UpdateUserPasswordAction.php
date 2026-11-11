<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\DTOs\Profile\UpdatePasswordData;
use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher;

final readonly class UpdateUserPasswordAction
{
    public function __construct(
        public Hasher $hasher,
    ) {}

    /**
     * Update user password securely.
     */
    public function execute(User $user, UpdatePasswordData $data): void
    {
        $user->forceFill([
            'password' => $this->hasher->make($data->newPassword),
        ])->save();
    }
}
