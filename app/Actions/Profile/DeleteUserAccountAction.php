<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;

final readonly class DeleteUserAccountAction
{
    public function __construct(
        public AuthManager $auth,
    ) {}

    /**
     * Log out user, delete account, and invalidate the session.
     */
    public function execute(User $user, Request $request): void
    {
        $this->auth->guard()->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
