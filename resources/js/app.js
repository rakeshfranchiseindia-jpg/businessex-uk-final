import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST || window.location.hostname,
    wsPort: Number(import.meta.env.VITE_REVERB_PORT || 8080),
    wssPort: Number(import.meta.env.VITE_REVERB_PORT || 443),
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME || 'http') === 'https',
    enabledTransports: ['ws', 'wss'],
    authEndpoint: '/broadcasting/auth',
});

const messageList = document.querySelector('[data-live-conversation]');
const messageForm = document.querySelector('[data-live-message-form]');

if (messageList && messageForm) {
    const conversationId = messageList.dataset.liveConversation;
    const currentUserId = Number(messageList.dataset.currentUserId);
    const status = document.querySelector('[data-live-status]');
    const input = messageForm.querySelector('textarea[name="message"]');
    const submit = messageForm.querySelector('button[type="submit"]');
    const csrfToken = messageForm.querySelector('input[name="_token"]').value;

    function appendMessage(message) {
        if (messageList.querySelector(`[data-message-id="${message.id}"]`)) return;

        const ownMessage = Number(message.sender_user_id) === currentUserId;
        const article = document.createElement('article');
        article.className = `inbox-message${ownMessage ? ' is-own-message' : ''}`;
        article.dataset.messageId = message.id;
        article.dataset.senderUserId = message.sender_user_id;

        const metadata = document.createElement('div');
        metadata.className = 'inbox-message-meta';
        const sender = document.createElement('strong');
        sender.textContent = ownMessage ? 'You' : message.sender_name;
        const time = document.createElement('time');
        time.dateTime = message.created_at;
        time.textContent = new Intl.DateTimeFormat(undefined, {
            dateStyle: 'medium',
            timeStyle: 'short',
        }).format(new Date(message.created_at));
        metadata.append(sender, time);

        const content = document.createElement('p');
        content.textContent = message.message;
        article.append(metadata, content);
        messageList.append(article);
        messageList.scrollTop = messageList.scrollHeight;
    }

    window.Echo.connector.pusher.connection.bind('connected', () => {
        status.textContent = 'Live chat connected';
        status.dataset.connection = 'connected';
    });
    window.Echo.connector.pusher.connection.bind('disconnected', () => {
        status.textContent = 'Reconnecting to live chat…';
        status.dataset.connection = 'disconnected';
    });
    window.Echo.connector.pusher.connection.bind('error', () => {
        status.textContent = 'Live connection unavailable. You can still send messages.';
        status.dataset.connection = 'error';
    });

    window.Echo.private(`conversation.${conversationId}`)
        .listen('.message.sent', appendMessage)
        .error(() => {
            status.textContent = 'Live connection unavailable. You can still send messages.';
            status.dataset.connection = 'error';
        });

    messageForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        const message = input.value.trim();
        if (!message) return;

        submit.disabled = true;
        status.textContent = 'Sending…';
        try {
            const response = await fetch(messageForm.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ message }),
            });
            const result = await response.json();
            if (!response.ok) {
                const errors = result.errors ? Object.values(result.errors).flat().join(' ') : result.message;
                throw new Error(errors || 'Message could not be sent. Please try again.');
            }

            appendMessage(result.message);
            input.value = '';
            status.textContent = 'Message sent';
        } catch (error) {
            status.textContent = error.message || 'Message could not be sent. Please try again.';
            status.dataset.connection = 'error';
        } finally {
            submit.disabled = false;
            input.focus();
        }
    });
}

const chatWidget = document.querySelector('[data-live-chat-widget]');

if (chatWidget) {
    const panel = chatWidget.querySelector('[data-chat-panel]');
    const openButton = chatWidget.querySelector('[data-chat-open]');
    const closeButton = chatWidget.querySelector('[data-chat-close]');
    const threadList = chatWidget.querySelector('[data-chat-threads]');
    const threadView = chatWidget.querySelector('[data-chat-thread]');
    const status = chatWidget.querySelector('[data-chat-status]');
    const form = chatWidget.querySelector('[data-chat-form]');
    const input = form.querySelector('textarea[name="message"]');
    const count = chatWidget.querySelector('[data-chat-count]');
    const currentUserId = Number(chatWidget.dataset.currentUserId);
    const conversations = new Map();
    const subscriptions = new Set();
    let activeConversationId = null;
    let unreadCount = 0;

    function makeMessage(message) {
        const ownMessage = Number(message.sender_user_id) === currentUserId;
        const article = document.createElement('article');
        article.className = `inbox-message${ownMessage ? ' is-own-message' : ''}`;
        article.dataset.messageId = message.id;

        const heading = document.createElement('strong');
        heading.textContent = ownMessage ? 'You' : message.sender_name;
        const time = document.createElement('time');
        time.dateTime = message.created_at;
        time.textContent = new Intl.DateTimeFormat(undefined, {
            dateStyle: 'short',
            timeStyle: 'short',
        }).format(new Date(message.created_at));
        const metadata = document.createElement('div');
        metadata.className = 'inbox-message-meta';
        metadata.append(heading, time);

        const text = document.createElement('p');
        text.textContent = message.message;
        article.append(metadata, text);
        return article;
    }

    function appendWidgetMessage(message) {
        if (Number(message.conversation_id) !== activeConversationId) return;
        if (threadView.querySelector(`[data-message-id="${message.id}"]`)) return;

        threadView.querySelector('.dashboard-live-chat-placeholder')?.remove();
        threadView.append(makeMessage(message));
        threadView.scrollTop = threadView.scrollHeight;
    }

    function updateThreadList() {
        threadList.replaceChildren();
        [...conversations.values()].forEach((conversation) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'dashboard-live-chat-contact';
            if (conversation.id === activeConversationId) button.setAttribute('aria-current', 'true');
            button.dataset.conversationId = conversation.id;

            const name = document.createElement('strong');
            name.textContent = conversation.counterpart;
            const profile = document.createElement('span');
            profile.textContent = conversation.profile_label;
            const preview = document.createElement('span');
            preview.textContent = conversation.last_message || 'Start a conversation';
            button.append(name, profile, preview);
            threadList.append(button);
        });
    }

    async function loadConversation(conversation) {
        activeConversationId = conversation.id;
        updateThreadList();
        threadView.replaceChildren();
        const loading = document.createElement('p');
        loading.className = 'dashboard-live-chat-placeholder';
        loading.textContent = 'Loading messages…';
        threadView.append(loading);
        form.hidden = true;

        try {
            const response = await fetch(conversation.messages_url, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            const result = await response.json();
            if (!response.ok) throw new Error(result.message || 'Could not load this conversation.');

            threadView.replaceChildren();
            if (result.messages.length === 0) {
                const placeholder = document.createElement('p');
                placeholder.className = 'dashboard-live-chat-placeholder';
                placeholder.textContent = 'Send a message to start chatting.';
                threadView.append(placeholder);
            } else {
                result.messages.forEach((message) => threadView.append(makeMessage(message)));
                threadView.scrollTop = threadView.scrollHeight;
            }
            form.dataset.replyUrl = conversation.reply_url;
            form.hidden = false;
            input.focus();
        } catch (error) {
            threadView.replaceChildren();
            const failure = document.createElement('p');
            failure.className = 'dashboard-live-chat-placeholder';
            failure.setAttribute('role', 'alert');
            failure.textContent = error.message;
            threadView.append(failure);
        }
    }

    function subscribe(conversation) {
        if (subscriptions.has(conversation.id)) return;
        subscriptions.add(conversation.id);
        window.Echo.private(`conversation.${conversation.id}`)
            .listen('.message.sent', (message) => {
                conversation.last_message = message.message;
                conversation.updated_at = message.created_at;
                updateThreadList();
                appendWidgetMessage(message);
                if (Number(message.sender_user_id) !== currentUserId && !panel.hidden) {
                    status.textContent = `New message from ${conversation.counterpart}`;
                } else if (Number(message.sender_user_id) !== currentUserId) {
                    unreadCount += 1;
                    count.textContent = unreadCount;
                    count.hidden = false;
                }
            })
            .error(() => {
                status.textContent = 'Live connection unavailable. You can still send messages.';
            });
    }

    async function loadConversations() {
        try {
            const response = await fetch(chatWidget.dataset.conversationsUrl, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            const result = await response.json();
            if (!response.ok) throw new Error(result.message || 'Could not load conversations.');

            result.conversations.forEach((conversation) => {
                conversation.id = Number(conversation.id);
                conversations.set(conversation.id, conversation);
                subscribe(conversation);
            });
            updateThreadList();
            status.textContent = conversations.size
                ? `${conversations.size} conversation${conversations.size === 1 ? '' : 's'}`
                : 'No conversations yet';
        } catch (error) {
            status.textContent = error.message;
        }
    }

    openButton.addEventListener('click', () => {
        panel.hidden = false;
        openButton.setAttribute('aria-expanded', 'true');
        unreadCount = 0;
        count.hidden = true;
    });
    closeButton.addEventListener('click', () => {
        panel.hidden = true;
        openButton.setAttribute('aria-expanded', 'false');
        openButton.focus();
    });
    document.querySelectorAll('[data-open-live-chat]').forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            openButton.click();
        });
    });
    threadList.addEventListener('click', (event) => {
        const button = event.target.closest('[data-conversation-id]');
        if (button) loadConversation(conversations.get(Number(button.dataset.conversationId)));
    });
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const message = input.value.trim();
        if (!message || !form.dataset.replyUrl) return;

        const submit = form.querySelector('button[type="submit"]');
        submit.disabled = true;
        status.textContent = 'Sending…';
        try {
            const response = await fetch(form.dataset.replyUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ message }),
            });
            const result = await response.json();
            if (!response.ok) {
                const errors = result.errors ? Object.values(result.errors).flat().join(' ') : result.message;
                throw new Error(errors || 'Message could not be sent. Please try again.');
            }

            appendWidgetMessage(result.message);
            const conversation = conversations.get(activeConversationId);
            conversation.last_message = result.message.message;
            updateThreadList();
            input.value = '';
            status.textContent = 'Message sent';
        } catch (error) {
            status.textContent = error.message;
        } finally {
            submit.disabled = false;
            input.focus();
        }
    });

    window.Echo.connector.pusher.connection.bind('connected', () => {
        status.textContent = conversations.size
            ? 'Live chat connected'
            : 'No conversations yet';
    });
    window.Echo.connector.pusher.connection.bind('disconnected', () => {
        status.textContent = 'Reconnecting to live chat…';
    });
    window.Echo.connector.pusher.connection.bind('error', () => {
        status.textContent = 'Live connection unavailable. You can still send messages.';
    });
    loadConversations();
}
