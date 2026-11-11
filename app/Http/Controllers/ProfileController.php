<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Profile\DeleteUserAccountAction;
use App\Actions\Profile\UpdateProfileInformationAction;
use App\Http\Requests\Profile\DeleteAccountRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(UpdateProfileRequest $request, UpdateProfileInformationAction $action): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $action->execute($user, $request->toDTO());

        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(DeleteAccountRequest $request, DeleteUserAccountAction $action): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $action->execute($user, $request);

        return redirect('/');
    }
}
