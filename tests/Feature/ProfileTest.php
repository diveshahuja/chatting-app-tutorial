<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/profile');

    $response->assertStatus(200);
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->patch('/profile', [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('profile.edit'));

    $user->refresh();

    $this->assertSame('Updated Name', $user->name);
    $this->assertSame('updated@example.com', $user->email);
});

test('user can update password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('old-password123'),
    ]);

    $response = $this->actingAs($user)->put('/password', [
        'current_password' => 'old-password123',
        'password' => 'new-password456',
        'password_confirmation' => 'new-password456',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertSessionHas('status', 'password-updated');

    $this->assertTrue(Hash::check('new-password456', $user->refresh()->password));
});

test('correct password must be provided to update password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('old-password123'),
    ]);

    $response = $this->actingAs($user)->put('/password', [
        'current_password' => 'wrong-current-password',
        'password' => 'new-password456',
        'password_confirmation' => 'new-password456',
    ]);

    $response->assertSessionHasErrors('current_password');
});

test('user can delete their account', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password123'),
    ]);

    $response = $this->actingAs($user)->delete('/profile', [
        'password' => 'password123',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull(User::find($user->id));
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password123'),
    ]);

    $response = $this->actingAs($user)->delete('/profile', [
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors('password');
    $this->assertNotNull(User::find($user->id));
});
