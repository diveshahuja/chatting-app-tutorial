<?php

use App\Models\Conversation;
use App\Models\User;

test('guests are redirected from conversations page', function () {
    $response = $this->get(route('conversations.index'));

    $response->assertRedirect(route('login'));
});

test('authenticated user can view conversations page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('conversations.index'));

    $response->assertStatus(200);
    $response->assertSee('Chat Hub');
});

test('user can start an individual conversation', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $response = $this->actingAs($user1)->post(route('conversations.store'), [
        'type' => 'individual',
        'user_id' => $user2->id,
    ]);

    $conversation = Conversation::where('type', 'individual')->first();

    $this->assertNotNull($conversation);
    $this->assertTrue($conversation->users->contains($user1));
    $this->assertTrue($conversation->users->contains($user2));
    $response->assertRedirect(route('conversations.index', ['c' => $conversation->id]));
});

test('user can start a group conversation', function () {
    $admin = User::factory()->create();
    $member1 = User::factory()->create();
    $member2 = User::factory()->create();

    $response = $this->actingAs($admin)->post(route('conversations.store'), [
        'type' => 'group',
        'name' => 'Tech Squad',
        'user_ids' => [$member1->id, $member2->id],
    ]);

    $conversation = Conversation::where('type', 'group')->first();

    $this->assertNotNull($conversation);
    $this->assertSame('Tech Squad', $conversation->name);
    $this->assertSame($admin->id, $conversation->created_by);
    $this->assertCount(3, $conversation->users);
    $response->assertRedirect(route('conversations.index', ['c' => $conversation->id]));
});

test('user can send message in a conversation', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $conversation = Conversation::create([
        'type' => 'individual',
        'created_by' => $user1->id,
    ]);
    $conversation->users()->attach([$user1->id, $user2->id]);

    $response = $this->actingAs($user1)->post(route('conversations.messages.store', $conversation), [
        'body' => 'Hello from Pest test!',
    ]);

    $response->assertRedirect(route('conversations.index', ['c' => $conversation->id]));

    $this->assertDatabaseHas('messages', [
        'conversation_id' => $conversation->id,
        'sender_id' => $user1->id,
        'body' => 'Hello from Pest test!',
    ]);

    $conversation->refresh();
    $this->assertNotNull($conversation->last_message_at);
});
