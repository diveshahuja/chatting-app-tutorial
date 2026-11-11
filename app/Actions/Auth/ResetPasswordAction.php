<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\DTOs\Auth\ResetPasswordData;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Str;

final readonly class ResetPasswordAction
{
    public function __construct(
        public PasswordBrokerManager $passwords,
        public Dispatcher $events,
        public Hasher $hasher,
    ) {}

    /**
     * Reset user password using the broker and dispatch event.
     */
    public function execute(ResetPasswordData $data): string
    {
        return $this->passwords->broker()->reset(
            $data->credentials(),
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => $this->hasher->make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                $this->events->dispatch(new PasswordReset($user));
            }
        );
    }
}
