<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\DTOs\Profile\UpdateProfileData;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;

final readonly class UpdateProfileInformationAction
{
    /**
     * Update user name and email.
     */
    public function execute(User $user, UpdateProfileData $data): void
    {
        $user->name = $data->name;
        $user->email = $data->email;

        if ($user instanceof MustVerifyEmail && $user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
    }
}
