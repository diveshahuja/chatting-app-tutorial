<x-app-layout>
    <x-slot:title>{{ __('Messages & Conversations') }}</x-slot:title>

    <x-slot:header>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="font-extrabold text-2xl text-white tracking-tight flex items-center gap-3">
                    <span>{{ __('Chat Hub') }}</span>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 font-bold uppercase tracking-wider">
                        {{ $conversations->count() }} {{ Str::plural('chat', $conversations->count()) }}
                    </span>
                </h1>
                <p class="mt-1 text-xs text-slate-400">
                    {{ __('Real-time individual and group messaging built on your database schema.') }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" onclick="openNewChatModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-linear-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 active:from-indigo-700 active:to-violet-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-indigo-600/30 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-150 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>{{ __('New Chat') }}</span>
                </button>
            </div>
        </div>
    </x-slot:header>

    @if (session('status'))
        <div class="mb-4">
            <x-auth-session-status :status="session('status')" />
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-4 rounded-xl bg-rose-950/40 border border-rose-800/50 text-xs font-semibold text-rose-300 space-y-1">
            @foreach ($errors->all() as $error)
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 7.5h.01" />
                    </svg>
                    <span>{{ $error }}</span>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Main Chat Interface Container -->
    <div class="h-[calc(100vh-13.5rem)] min-h-[640px] max-h-[860px] rounded-3xl bg-slate-900/90 border border-slate-800/90 shadow-2xl shadow-black/60 overflow-hidden flex flex-col md:flex-row relative backdrop-blur-2xl">

        <!-- ========================================== -->
        <!-- LEFT PANE: CONVERSATION LIST (SIDEBAR)    -->
        <!-- ========================================== -->
        <aside class="w-full md:w-80 lg:w-96 flex flex-col border-b md:border-b-0 md:border-r border-slate-800/80 bg-slate-950/40 shrink-0 {{ $activeConversation ? 'hidden md:flex' : 'flex' }}">
            <!-- Search & Actions Bar -->
            <div class="p-4 border-b border-slate-800/80 space-y-3">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>
                    <input
                        type="text"
                        id="chat-search"
                        oninput="filterConversations(this.value)"
                        placeholder="{{ __('Search chats or people...') }}"
                        class="w-full pl-9 pr-4 py-2 bg-slate-900/90 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs text-white placeholder-slate-500 transition outline-hidden"
                    />
                </div>

                <!-- Chat Filter Pills -->
                <div class="flex items-center gap-1.5 p-1 bg-slate-900/80 border border-slate-800/80 rounded-xl text-xs">
                    <button type="button" onclick="setChatFilter('all', this)" class="chat-tab-btn flex-1 py-1 px-2.5 rounded-lg font-semibold text-center transition bg-indigo-600/30 text-indigo-300 border border-indigo-500/30">
                        {{ __('All') }}
                    </button>
                    <button type="button" onclick="setChatFilter('individual', this)" class="chat-tab-btn flex-1 py-1 px-2.5 rounded-lg font-semibold text-center text-slate-400 hover:text-white transition">
                        {{ __('Direct') }}
                    </button>
                    <button type="button" onclick="setChatFilter('group', this)" class="chat-tab-btn flex-1 py-1 px-2.5 rounded-lg font-semibold text-center text-slate-400 hover:text-white transition">
                        {{ __('Groups') }}
                    </button>
                </div>
            </div>

            <!-- Conversations Scrollable List -->
            <div id="conversations-list" class="flex-1 overflow-y-auto divide-y divide-slate-800/40 p-2 space-y-1">
                @forelse ($conversations as $conversation)
                    @php
                        $isActive = $activeConversation && $activeConversation->id === $conversation->id;
                        $displayName = $conversation->getDisplayName($currentUser);
                        $initials = $conversation->getDisplayInitials($currentUser);
                        $latest = $conversation->latestMessage;
                        $pivot = $conversation->users->firstWhere('id', $currentUser->id)?->pivot;
                        $isUnread = $conversation->last_message_at && (! $pivot?->last_read_at || $conversation->last_message_at->gt($pivot->last_read_at));
                        $isGroup = $conversation->type === 'group';
                    @endphp

                    <a
                        href="{{ route('conversations.index', ['c' => $conversation->id]) }}"
                        data-name="{{ strtolower($displayName) }}"
                        data-type="{{ $conversation->type }}"
                        class="conversation-item group flex items-center gap-3.5 p-3 rounded-2xl transition-all duration-150 {{ $isActive ? 'bg-indigo-600/20 border border-indigo-500/40 shadow-xs' : 'hover:bg-slate-800/50 border border-transparent' }}"
                    >
                        <!-- Avatar -->
                        <div class="relative shrink-0">
                            @if ($isGroup)
                                <div class="w-11 h-11 rounded-2xl bg-linear-to-tr from-purple-600 to-indigo-600 text-white font-extrabold text-xs flex items-center justify-center uppercase shadow-md shadow-purple-600/25 ring-2 ring-slate-800">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                    </svg>
                                </div>
                            @else
                                <div class="w-11 h-11 rounded-2xl bg-linear-to-tr from-indigo-500 to-violet-500 text-white font-extrabold text-xs flex items-center justify-center uppercase shadow-md shadow-indigo-500/25 ring-2 ring-slate-800">
                                    {{ $initials }}
                                </div>
                                <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-500 ring-2 ring-slate-900"></span>
                            @endif
                        </div>

                        <!-- Info & Snippet -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-1">
                                <span class="font-bold text-sm text-white truncate {{ $isActive ? 'text-indigo-200' : '' }}">
                                    {{ $displayName }}
                                </span>
                                <span class="text-[10px] text-slate-500 shrink-0 font-medium">
                                    @if ($conversation->last_message_at)
                                        {{ $conversation->last_message_at->diffForHumans(short: true) }}
                                    @else
                                        {{ $conversation->created_at->diffForHumans(short: true) }}
                                    @endif
                                </span>
                            </div>

                            <div class="flex items-center justify-between gap-2 mt-1">
                                <p class="text-xs text-slate-400 truncate leading-snug">
                                    @if ($latest)
                                        @if ($latest->sender_id === $currentUser->id)
                                            <span class="text-indigo-400 font-semibold">{{ __('You: ') }}</span>
                                        @elseif ($isGroup)
                                            <span class="text-slate-300 font-semibold">{{ Str::before($latest->sender?->name ?? 'User', ' ') }}: </span>
                                        @endif
                                        <span>{{ Str::limit($latest->body, 32) }}</span>
                                    @else
                                        <span class="italic text-slate-500">{{ __('No messages yet') }}</span>
                                    @endif
                                </p>

                                @if ($isUnread && ! $isActive)
                                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-500 shrink-0 ring-2 ring-indigo-500/30 animate-pulse"></span>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <!-- Empty State for Sidebar -->
                    <div class="p-8 text-center my-auto">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a.75.75 0 0 1-1.154-.794l.43-1.722A7.472 7.472 0 0 1 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-sm text-white">{{ __('No chats yet') }}</h4>
                        <p class="mt-1 text-xs text-slate-400">{{ __('Start a direct chat or create a group to begin messaging.') }}</p>
                        <button type="button" onclick="openNewChatModal()" class="mt-4 px-3 py-1.5 rounded-lg text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white transition">
                            {{ __('Start Chatting') }}
                        </button>
                    </div>
                @endforelse
            </div>

            <!-- Current User Status Footer -->
            <div class="p-3.5 border-t border-slate-800/80 bg-slate-900/60 flex items-center justify-between">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="relative">
                        <div class="w-8 h-8 rounded-full bg-linear-to-tr from-indigo-500 to-purple-500 text-white font-bold text-xs flex items-center justify-center uppercase">
                            {{ substr($currentUser->name, 0, 2) }}
                        </div>
                        <span class="absolute bottom-0 right-0 w-2 h-2 rounded-full bg-emerald-500 ring-2 ring-slate-900"></span>
                    </div>
                    <div class="truncate">
                        <div class="text-xs font-bold text-white truncate">{{ $currentUser->name }}</div>
                        <div class="text-[10px] text-emerald-400 font-medium flex items-center gap-1">
                            <span class="w-1 h-1 rounded-full bg-emerald-400"></span>
                            <span>Online</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('profile.edit') }}" class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition" title="{{ __('Settings') }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.27.1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.27-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.107-1.204l-.527-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </a>
            </div>
        </aside>

        <!-- ========================================== -->
        <!-- RIGHT PANE: ACTIVE CHAT THREAD / EMPTY    -->
        <!-- ========================================== -->
        <main class="flex-1 flex flex-col bg-slate-900/60 overflow-hidden {{ $activeConversation ? 'flex' : 'hidden md:flex' }}">
            @if ($activeConversation)
                @php
                    $activeName = $activeConversation->getDisplayName($currentUser);
                    $activeInitials = $activeConversation->getDisplayInitials($currentUser);
                    $isGroup = $activeConversation->type === 'group';
                    $participantsCount = $activeConversation->users->count();
                @endphp

                <!-- Active Chat Header -->
                <div class="px-6 py-4 border-b border-slate-800/80 bg-slate-900/90 backdrop-blur-md flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-3.5">
                        <!-- Mobile Back Button -->
                        <a href="{{ route('conversations.index') }}" class="md:hidden p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                            </svg>
                        </a>

                        <!-- Avatar -->
                        <div class="relative">
                            @if ($isGroup)
                                <div class="w-10 h-10 rounded-2xl bg-linear-to-tr from-purple-600 to-indigo-600 text-white font-extrabold text-xs flex items-center justify-center uppercase shadow-md shadow-purple-600/30 ring-2 ring-slate-800">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                    </svg>
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-2xl bg-linear-to-tr from-indigo-500 to-violet-500 text-white font-extrabold text-xs flex items-center justify-center uppercase shadow-md shadow-indigo-500/30 ring-2 ring-slate-800">
                                    {{ $activeInitials }}
                                </div>
                                <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-slate-900"></span>
                            @endif
                        </div>

                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-extrabold text-sm sm:text-base text-white tracking-tight">
                                    {{ $activeName }}
                                </h3>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $isGroup ? 'bg-purple-500/20 text-purple-300 border border-purple-500/30' : 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30' }}">
                                    {{ $isGroup ? __('Group') : __('Direct') }}
                                </span>
                            </div>
                            <div class="text-[11px] text-slate-400 flex items-center gap-2">
                                @if ($isGroup)
                                    <span>{{ $participantsCount }} {{ __('participants') }}</span>
                                    <span>•</span>
                                    <span>{{ __('Created by :name', ['name' => $activeConversation->creator?->name ?? 'Admin']) }}</span>
                                @else
                                    <span class="flex items-center gap-1 text-emerald-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                        <span>{{ __('Active now') }}</span>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Header Action Tools -->
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="toggleGroupDetailsModal()" class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition" title="{{ __('Conversation Info') }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Messages Feed -->
                <div id="messages-container" class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-4">
                    <!-- Conversation Start Greeting Pill -->
                    <div class="text-center my-4">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-semibold bg-slate-800/80 border border-slate-700/60 text-slate-400 shadow-xs">
                            <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                            <span>{{ __('End-to-end conversation initialized on :date', ['date' => $activeConversation->created_at->format('M d, Y')]) }}</span>
                        </div>
                    </div>

                    @forelse ($activeConversation->messages as $message)
                        @php
                            $isMine = $message->sender_id === $currentUser->id;
                            $sender = $message->sender;
                        @endphp

                        @if ($isMine)
                            <!-- Sent by Current User (Right aligned) -->
                            <div class="flex justify-end items-end gap-2 group">
                                <div class="max-w-[82%] sm:max-w-md space-y-1">
                                    <div class="px-4 py-3 rounded-2xl rounded-tr-xs bg-linear-to-r from-indigo-600 via-indigo-500 to-violet-600 text-white shadow-md shadow-indigo-600/20 text-xs sm:text-sm leading-relaxed wrap-break-word">
                                        {!! nl2br(e($message->body)) !!}
                                    </div>
                                    <div class="flex items-center justify-end gap-1.5 text-[10px] text-slate-400 font-medium px-1">
                                        @if ($message->edited_at)
                                            <span class="italic text-slate-500">{{ __('(edited)') }}</span>
                                        @endif
                                        <span>{{ $message->created_at->format('h:i A') }}</span>
                                        <!-- Sent / Read double checkmark -->
                                        <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5m-15 0 6 6 9-13.5" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Received from Other Participant (Left aligned) -->
                            <div class="flex items-end gap-2.5 group">
                                <div class="w-7 h-7 rounded-xl bg-linear-to-tr from-slate-700 to-slate-600 text-white font-bold text-[10px] flex items-center justify-center uppercase shrink-0 ring-1 ring-slate-700">
                                    {{ substr($sender?->name ?? 'User', 0, 2) }}
                                </div>

                                <div class="max-w-[82%] sm:max-w-md space-y-1">
                                    @if ($isGroup)
                                        <div class="text-[11px] font-bold text-indigo-300 px-1">
                                            {{ $sender?->name ?? 'Participant' }}
                                        </div>
                                    @endif
                                    <div class="px-4 py-3 rounded-2xl rounded-tl-xs bg-slate-800/90 border border-slate-700/60 text-slate-100 shadow-sm text-xs sm:text-sm leading-relaxed wrap-break-word">
                                        {!! nl2br(e($message->body)) !!}
                                    </div>
                                    <div class="flex items-center gap-1.5 text-[10px] text-slate-400 font-medium px-1">
                                        <span>{{ $message->created_at->format('h:i A') }}</span>
                                        @if ($message->edited_at)
                                            <span class="italic text-slate-500">{{ __('(edited)') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <!-- No messages yet in this conversation -->
                        <div class="py-12 text-center my-auto">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.502 49.188 49.188 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                </svg>
                            </div>
                            <h4 class="font-bold text-sm text-white">{{ __('Start the conversation!') }}</h4>
                            <p class="mt-1 text-xs text-slate-400">{{ __('Send a message below to kick off this chat.') }}</p>
                        </div>
                    @endforelse
                </div>

                <!-- Message Input Footer Bar -->
                <div class="p-3 sm:p-4 border-t border-slate-800/80 bg-slate-900/90 backdrop-blur-md shrink-0">
                    <form method="POST" action="{{ route('conversations.messages.store', $activeConversation) }}" class="flex items-center gap-2">
                        @csrf

                        <!-- Message Input with enter key handler -->
                        <div class="relative flex-1">
                            <input
                                type="text"
                                name="body"
                                id="message-body"
                                required
                                autocomplete="off"
                                placeholder="{{ __('Type your message... (Press Enter to send)') }}"
                                class="w-full px-4 py-3 bg-slate-950/70 border border-slate-700/80 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/15 rounded-2xl text-xs sm:text-sm text-white placeholder-slate-500 transition outline-hidden"
                            />
                        </div>

                        <!-- Send Button -->
                        <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-linear-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 active:from-indigo-700 active:to-violet-700 text-white font-bold text-xs uppercase tracking-wider rounded-2xl shadow-lg shadow-indigo-600/30 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-150 cursor-pointer shrink-0">
                            <span>{{ __('Send') }}</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                            </svg>
                        </button>
                    </form>
                </div>
            @else
                <!-- No Active Conversation Selected State -->
                <div class="flex-1 flex flex-col items-center justify-center p-8 text-center my-auto">
                    <div class="w-16 h-16 rounded-3xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center mb-4 shadow-xl shadow-indigo-500/10">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a.75.75 0 0 1-1.154-.794l.43-1.722A7.472 7.472 0 0 1 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                        </svg>
                    </div>

                    <h2 class="text-xl sm:text-2xl font-extrabold text-white tracking-tight">
                        {{ __('Select a Conversation') }}
                    </h2>
                    <p class="mt-2 text-xs sm:text-sm text-slate-400 max-w-sm leading-relaxed">
                        {{ __('Pick a conversation from the sidebar or start a new direct message or group chat.') }}
                    </p>

                    <button type="button" onclick="openNewChatModal()" class="mt-6 inline-flex items-center gap-2 px-5 py-2.5 bg-linear-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-indigo-600/30 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>{{ __('Start a New Chat') }}</span>
                    </button>
                </div>
            @endif
        </main>
    </div>

    <!-- ========================================== -->
    <!-- MODAL: NEW CONVERSATION (DIRECT OR GROUP)  -->
    <!-- ========================================== -->
    <div id="new-chat-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-md p-4 sm:p-6 flex items-center justify-center">
        <div class="relative w-full max-w-lg rounded-3xl bg-slate-900 border border-slate-800 shadow-2xl p-6 sm:p-8 space-y-6 animate-in fade-in zoom-in-95 duration-150">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <div>
                    <h3 class="font-extrabold text-lg text-white tracking-tight">
                        {{ __('Start New Conversation') }}
                    </h3>
                    <p class="text-xs text-slate-400">{{ __('Choose a participant or create a collaborative group.') }}</p>
                </div>
                <button type="button" onclick="closeNewChatModal()" class="p-1.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Tab Switcher: Direct vs Group -->
            <div class="flex items-center gap-2 p-1 bg-slate-950/60 border border-slate-800 rounded-xl text-xs">
                <button type="button" id="tab-btn-direct" onclick="switchModalTab('direct')" class="flex-1 py-2 px-3 rounded-lg font-bold text-center transition bg-indigo-600 text-white shadow-xs">
                    {{ __('Direct Message') }}
                </button>
                <button type="button" id="tab-btn-group" onclick="switchModalTab('group')" class="flex-1 py-2 px-3 rounded-lg font-bold text-center text-slate-400 hover:text-white transition">
                    {{ __('Group Chat') }}
                </button>
            </div>

            <!-- TAB 1: Direct Message Form -->
            <div id="tab-content-direct" class="space-y-4">
                <div class="text-xs font-semibold text-slate-300">
                    {{ __('Select a registered user to chat with:') }}
                </div>

                <div class="max-h-60 overflow-y-auto space-y-1.5 divide-y divide-slate-800/40">
                    @forelse ($availableUsers as $user)
                        <form method="POST" action="{{ route('conversations.store') }}">
                            @csrf
                            <input type="hidden" name="type" value="individual">
                            <input type="hidden" name="user_id" value="{{ $user->id }}">

                            <button type="submit" class="w-full flex items-center justify-between p-3 rounded-xl hover:bg-slate-800/60 transition text-left group cursor-pointer">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-linear-to-tr from-indigo-500 to-violet-500 text-white font-bold text-xs flex items-center justify-center uppercase shadow-xs">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-white group-hover:text-indigo-300 transition">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                                <span class="text-xs font-semibold text-indigo-400 opacity-0 group-hover:opacity-100 transition flex items-center gap-1">
                                    <span>Chat</span>
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                    </svg>
                                </span>
                            </button>
                        </form>
                    @empty
                        <div class="p-6 text-center text-xs text-slate-400">
                            {{ __('No other registered users found. Register another account or seed users to test direct messaging.') }}
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- TAB 2: Group Chat Form -->
            <form id="tab-content-group" method="POST" action="{{ route('conversations.store') }}" class="hidden space-y-4">
                @csrf
                <input type="hidden" name="type" value="group">

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">{{ __('Group Name') }}</label>
                    <input type="text" name="name" required placeholder="{{ __('e.g. Design Team, Project Alpha') }}" class="w-full px-4 py-2.5 bg-slate-950/70 border border-slate-700/80 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/15 rounded-xl text-sm text-white placeholder-slate-500 outline-hidden">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">{{ __('Select Group Participants') }}</label>
                    <div class="max-h-48 overflow-y-auto space-y-1 p-2 rounded-xl bg-slate-950/60 border border-slate-800">
                        @forelse ($availableUsers as $user)
                            <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-800/40 cursor-pointer text-xs">
                                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="w-4 h-4 rounded-md border-slate-700 bg-slate-900 text-indigo-600 focus:ring-indigo-500/20">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-slate-700 text-white font-bold text-[10px] flex items-center justify-center uppercase">
                                        {{ substr($user->name, 0, 2) }}
                                    </span>
                                    <span class="text-slate-200 font-medium">{{ $user->name }}</span>
                                    <span class="text-slate-500">({{ $user->email }})</span>
                                </div>
                            </label>
                        @empty
                            <div class="p-4 text-center text-xs text-slate-400">
                                {{ __('No other users available.') }}
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 bg-linear-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-indigo-600/30 transition">
                        {{ __('Create Group & Start Chat') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL: CONVERSATION DETAILS / PARTICIPANTS -->
    <!-- ========================================== -->
    @if ($activeConversation)
        <div id="group-details-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-md p-4 sm:p-6 flex items-center justify-center">
            <div class="relative w-full max-w-md rounded-3xl bg-slate-900 border border-slate-800 shadow-2xl p-6 sm:p-8 space-y-6 animate-in fade-in zoom-in-95 duration-150">
                <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                    <div>
                        <h3 class="font-extrabold text-lg text-white tracking-tight">
                            {{ $activeConversation->type === 'group' ? __('Group Information') : __('Conversation Details') }}
                        </h3>
                        <p class="text-xs text-slate-400">{{ __('Participants and membership history.') }}</p>
                    </div>
                    <button type="button" onclick="toggleGroupDetailsModal()" class="p-1.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="p-4 rounded-2xl bg-slate-950/60 border border-slate-800 space-y-2 text-xs">
                        <div class="flex justify-between py-1 border-b border-slate-800/60">
                            <span class="text-slate-400">{{ __('Conversation Type') }}</span>
                            <span class="font-bold text-white capitalize">{{ $activeConversation->type }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-800/60">
                            <span class="text-slate-400">{{ __('Created Date') }}</span>
                            <span class="font-mono text-slate-200">{{ $activeConversation->created_at->format('M d, Y') }}</span>
                        </div>
                        @if ($activeConversation->creator)
                            <div class="flex justify-between py-1">
                                <span class="text-slate-400">{{ __('Created By') }}</span>
                                <span class="font-bold text-indigo-300">{{ $activeConversation->creator->name }}</span>
                            </div>
                        @endif
                    </div>

                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">
                            {{ __('Participants (:count)', ['count' => $activeConversation->users->count()]) }}
                        </h4>
                        <div class="max-h-56 overflow-y-auto space-y-2 divide-y divide-slate-800/40">
                            @foreach ($activeConversation->users as $member)
                                <div class="pt-2 first:pt-0 flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full bg-linear-to-tr from-indigo-500 to-purple-500 text-white font-bold text-[10px] flex items-center justify-center uppercase">
                                            {{ substr($member->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-white flex items-center gap-1.5">
                                                <span>{{ $member->name }}</span>
                                                @if ($member->id === $currentUser->id)
                                                    <span class="text-[9px] px-1.5 py-0.2 rounded-md bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">You</span>
                                                @endif
                                                @if ($member->id === $activeConversation->created_by)
                                                    <span class="text-[9px] px-1.5 py-0.2 rounded-md bg-purple-500/20 text-purple-300 border border-purple-500/30">Admin</span>
                                                @endif
                                            </div>
                                            <div class="text-[10px] text-slate-500">
                                                {{ __('Joined') }} {{ $member->pivot?->joined_at ? \Carbon\Carbon::parse($member->pivot->joined_at)->diffForHumans() : 'Recently' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Client-side Interactive JavaScript -->
    <script>
        // Auto-scroll messages feed to latest message
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('messages-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }

            const input = document.getElementById('message-body');
            if (input) {
                input.focus();
            }
        });

        // Filter conversations list in sidebar
        function filterConversations(query) {
            const term = query.toLowerCase().trim();
            const items = document.querySelectorAll('.conversation-item');

            items.forEach(item => {
                const name = item.getAttribute('data-name') || '';
                if (!term || name.includes(term)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Tab filter for All / Direct / Groups
        function setChatFilter(type, button) {
            document.querySelectorAll('.chat-tab-btn').forEach(btn => {
                btn.className = 'chat-tab-btn flex-1 py-1 px-2.5 rounded-lg font-semibold text-center text-slate-400 hover:text-white transition';
            });

            button.className = 'chat-tab-btn flex-1 py-1 px-2.5 rounded-lg font-semibold text-center transition bg-indigo-600/30 text-indigo-300 border border-indigo-500/30';

            const items = document.querySelectorAll('.conversation-item');
            items.forEach(item => {
                const itemType = item.getAttribute('data-type');
                if (type === 'all' || itemType === type) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // New Chat Modal Controls
        function openNewChatModal() {
            document.getElementById('new-chat-modal').classList.remove('hidden');
        }

        function closeNewChatModal() {
            document.getElementById('new-chat-modal').classList.add('hidden');
        }

        function switchModalTab(tab) {
            const btnDirect = document.getElementById('tab-btn-direct');
            const btnGroup = document.getElementById('tab-btn-group');
            const contentDirect = document.getElementById('tab-content-direct');
            const contentGroup = document.getElementById('tab-content-group');

            if (tab === 'direct') {
                btnDirect.className = 'flex-1 py-2 px-3 rounded-lg font-bold text-center transition bg-indigo-600 text-white shadow-xs';
                btnGroup.className = 'flex-1 py-2 px-3 rounded-lg font-bold text-center text-slate-400 hover:text-white transition';
                contentDirect.classList.remove('hidden');
                contentGroup.classList.add('hidden');
            } else {
                btnGroup.className = 'flex-1 py-2 px-3 rounded-lg font-bold text-center transition bg-indigo-600 text-white shadow-xs';
                btnDirect.className = 'flex-1 py-2 px-3 rounded-lg font-bold text-center text-slate-400 hover:text-white transition';
                contentGroup.classList.remove('hidden');
                contentDirect.classList.add('hidden');
            }
        }

        // Toggle Group Details Modal
        function toggleGroupDetailsModal() {
            const modal = document.getElementById('group-details-modal');
            if (modal) {
                modal.classList.toggle('hidden');
            }
        }
    </script>
</x-app-layout>
