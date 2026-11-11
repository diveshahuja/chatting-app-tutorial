<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = [
        'type',
        'name',
        'created_by',
        'last_message_at',
    ];

    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conversation_user', 'conversation_id', 'user_id')
            ->withPivot(['joined_at', 'last_read_at'])
            ->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'conversation_id');
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class, 'conversation_id')->latestOfMany();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getDisplayName(?User $currentUser = null): string
    {
        if ($this->type === 'group' && filled($this->name)) {
            return $this->name;
        }

        $currentUser ??= auth()->user();
        $otherUser = $this->users->first(fn (User $user) => $user->id !== $currentUser?->id);

        return $otherUser?->name ?? $this->name ?? __('Direct Message');
    }

    public function getDisplayInitials(?User $currentUser = null): string
    {
        $title = $this->getDisplayName($currentUser);

        $words = preg_split('/\s+/', trim($title)) ?: [];
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1).substr($words[1], 0, 1));
        }

        return strtoupper(substr($title, 0, 2));
    }

    public function getOtherUser(?User $currentUser = null): ?User
    {
        $currentUser ??= auth()->user();

        return $this->users->first(fn (User $user) => $user->id !== $currentUser?->id);
    }
}
