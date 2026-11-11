<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use Illuminate\Auth\Passwords\PasswordBrokerManager;

final readonly class SendPasswordResetNotificationAction
{
    public function __construct(
        public PasswordBrokerManager $passwords,
    ) {}

    /**
     * Send password reset link to user.
     */
    public function execute(string $email): string
    {
        return $this->passwords->broker()->sendResetLink([
            'email' => $email,
        ]);
    }
}
