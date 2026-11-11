<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Profile\UpdateUserPasswordAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(UpdatePasswordRequest $request, UpdateUserPasswordAction $action): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $action->execute($user, $request->toDTO());

        return back()->with('status', 'password-updated');
    }
}
