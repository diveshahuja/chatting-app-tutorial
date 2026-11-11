<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConversationController extends Controller
{
    /**
     * Display a listing of conversations and the active chat room.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        $conversations = $user->conversations()
            ->with([
                'users' => fn ($query) => $query->orderBy('name'),
                'latestMessage.sender',
            ])
            ->orderByRaw('COALESCE(conversations.last_message_at, conversations.created_at) DESC')
            ->get();

        $activeConversation = null;
        $activeId = $request->query('c');

        if ($activeId) {
            $activeConversation = $conversations->firstWhere('id', (int) $activeId);
        }

        if (! $activeConversation && $conversations->isNotEmpty()) {
            $activeConversation = $conversations->first();
        }

        if ($activeConversation) {
            $activeConversation->load([
                'messages' => fn ($query) => $query->with('sender')->orderBy('created_at', 'asc'),
                'creator',
            ]);

            $activeConversation->users()->updateExistingPivot($user->id, [
                'last_read_at' => now(),
            ]);
        }

        $availableUsers = User::query()
            ->where('id', '!=', $user->id)
            ->orderBy('name')
            ->get();

        return view('conversations.index', [
            'conversations' => $conversations,
            'activeConversation' => $activeConversation,
            'availableUsers' => $availableUsers,
            'currentUser' => $user,
        ]);
    }

    /**
     * Store a newly created conversation (individual or group).
     */
    public function store(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $type = $request->input('type', 'individual');

        if ($type === 'group') {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'user_ids' => ['required', 'array', 'min:1'],
                'user_ids.*' => ['required', 'exists:users,id'],
            ]);

            $conversation = Conversation::create([
                'name' => $validated['name'],
                'type' => 'group',
                'created_by' => $user->id,
                'last_message_at' => now(),
            ]);

            $members = collect($validated['user_ids'])
                ->push($user->id)
                ->unique()
                ->mapWithKeys(fn ($id) => [
                    $id => [
                        'joined_at' => now(),
                        'last_read_at' => (int) $id === $user->id ? now() : null,
                    ],
                ])
                ->all();

            $conversation->users()->attach($members);

            return redirect()->route('conversations.index', ['c' => $conversation->id])
                ->with('status', __('Group created successfully.'));
        }

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $targetUserId = (int) $validated['user_id'];

        if ($targetUserId === $user->id) {
            return back()->withErrors(['user_id' => __('You cannot chat with yourself.')]);
        }

        $existing = $user->conversations()
            ->where('type', 'individual')
            ->whereHas('users', fn ($q) => $q->where('users.id', $targetUserId))
            ->first();

        if ($existing) {
            return redirect()->route('conversations.index', ['c' => $existing->id]);
        }

        $conversation = Conversation::create([
            'type' => 'individual',
            'created_by' => $user->id,
            'last_message_at' => now(),
        ]);

        $conversation->users()->attach([
            $user->id => ['joined_at' => now(), 'last_read_at' => now()],
            $targetUserId => ['joined_at' => now(), 'last_read_at' => null],
        ]);

        return redirect()->route('conversations.index', ['c' => $conversation->id]);
    }

    /**
     * Send a new message to the active conversation.
     */
    public function sendMessage(Request $request, Conversation $conversation): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        abort_unless(
            $conversation->users()->where('users.id', $user->id)->exists(),
            403,
            'You are not a participant in this conversation.'
        );

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => $user->id,
            'body' => $validated['body'],
        ]);

        $conversation->update([
            'last_message_at' => now(),
        ]);

        $conversation->users()->updateExistingPivot($user->id, [
            'last_read_at' => now(),
        ]);
        broadcast(new MessageSent($message))->toOthers();
        return redirect()->route('conversations.index', ['c' => $conversation->id]);
    }
}
