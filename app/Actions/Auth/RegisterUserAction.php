<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\DTOs\Auth\RegisterUserData;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Hashing\Hasher;

final readonly class RegisterUserAction
{
    public function __construct(
        public AuthManager $auth,
        public Dispatcher $events,
        public Hasher $hasher,
    ) {}

    /**
     * Register a new user, dispatch event, and authenticate.
     */
    public function execute(RegisterUserData $data): User
    {
        $user = User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => $this->hasher->make($data->password),
        ]);

        $this->events->dispatch(new Registered($user));

        $this->auth->guard()->login($user);

        return $user;
    }
}
