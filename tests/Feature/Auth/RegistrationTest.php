<?php

use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));

    $this->assertDatabaseHas('users', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
    ]);
});

test('new users cannot register with invalid email', function () {
    $response = $this->post('/register', [
        'name' => 'Jane Doe',
        'email' => 'not-an-email',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});

test('new users cannot register with existing email', function () {
    User::factory()->create([
        'email' => 'jane@example.com',
    ]);

    $response = $this->post('/register', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});

test('new users cannot register with mismatched password confirmation', function () {
    $response = $this->post('/register', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => 'password123',
        'password_confirmation' => 'different-password',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('password');
});
