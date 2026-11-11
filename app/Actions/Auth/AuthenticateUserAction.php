<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Auth\AuthManager;
use Illuminate\Validation\ValidationException;

final readonly class AuthenticateUserAction
{
    public function __construct(
        public AuthManager $auth,
    ) {}

    /**
     * Authenticate the user credentials while enforcing rate limiting and session regeneration.
     *
     * @throws ValidationException
     */
    public function execute(LoginRequest $request): void
    {
        $request->ensureIsNotRateLimited();

        $credentials = $request->toCredentials();

        if (! $this->auth->guard()->attempt($credentials->credentials(), $credentials->remember)) {
            $request->hitRateLimiter();

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $request->clearRateLimiter();

        $request->session()->regenerate();
    }
}
