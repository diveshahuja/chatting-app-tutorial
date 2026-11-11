import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});
let presenceChannel;
const conversationId = new URLSearchParams(window.location.search).get('c');
window.Echo.private(`conversation.${conversationId}`)
    .listen('.message.sent', (event) => {
        console.log('message sent', event);
        appendMessageToConversation(conversationId, event);
    });

presenceChannel = window.Echo.join(`chat.${conversationId}`)
    .here((users) => {
        console.log('Online users received', users);
    })
    .joining((user) => {
        console.log("Joined user", user);
    })
    .leaving((user) => {
        console.log("Leaving user", user);
    })
    .listenForWhisper('typing', (event) => {
        console.log('typing', event);
    })

function appendMessageToConversation(conversationId, message) {
    if (message.sender_id === window.authId){
        return;
    }
    let html = `<div class="flex items-end gap-2.5 group">
        <div class="w-7 h-7 rounded-xl bg-linear-to-tr from-slate-700 to-slate-600 text-white font-bold text-[10px] flex items-center justify-center uppercase shrink-0 ring-1 ring-slate-700">
            ${message.sender_name.substring(0, 2)}
        </div>

        <div class="max-w-[82%] sm:max-w-md space-y-1">
            <div class="px-4 py-3 rounded-2xl rounded-tl-xs bg-slate-800/90 border border-slate-700/60 text-slate-100 shadow-sm text-xs sm:text-sm leading-relaxed wrap-break-word">
                ${message.body}
            </div>
            <div class="flex items-center gap-1.5 text-[10px] text-slate-400 font-medium px-1">
                <span>${message.created_at}</span>
            </div>
        </div>
    </div>`;
    document.querySelector('#messages-container').insertAdjacentHTML('beforeend', html);

}
