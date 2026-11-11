<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('conversation.{conversationId}', function (User $user, int $conversationId) {
    return $user->conversations()->where('conversations.id', $conversationId)->exists();
});

Broadcast::channel('chat.{conversationId}', function(User $user, int $conversationId) {
    $isParticipant = $user->conversations()->where('conversations.id', $conversationId)->exists();
    if (!$isParticipant) {
        return false;
    }
    return [
        'id' => $user->id,
        'name' => $user->name,
    ];
});
