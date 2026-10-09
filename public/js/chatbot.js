(() => {
  const widget = document.querySelector('[data-chatbot]');
  if (!widget) return;

  const launcher = widget.querySelector('[data-chatbot-open]');
  const panel = widget.querySelector('[data-chatbot-panel]');
  const closeButton = widget.querySelector('[data-chatbot-close]');
  const messages = widget.querySelector('[data-chatbot-messages]');
  const messageForm = widget.querySelector('[data-chatbot-message-form]');
  const messageInput = messageForm.elements.message;
  const sendButton = widget.querySelector('[data-chatbot-send]');
  const fallback = widget.querySelector('[data-chatbot-fallback]');
  const leadForm = widget.querySelector('[data-chatbot-lead-form]');
  const leadMessage = widget.querySelector('[data-chatbot-lead-message]');

  function addMessage(text, sender, isError = false, linkUrl = null) {
    const bubble = document.createElement('p');
    bubble.className = `bx-chatbot-bubble is-${sender}${isError ? ' is-error' : ''}`;
    bubble.textContent = text;
    if (linkUrl && linkUrl.startsWith('/') && !linkUrl.startsWith('//')) {
      const link = document.createElement('a');
      link.href = linkUrl;
      link.textContent = 'Open this page';
      link.className = 'bx-chatbot-answer-link';
      bubble.append(document.createElement('br'), link);
    }
    messages.append(bubble);
    messages.scrollTop = messages.scrollHeight;
    return bubble;
  }

  function csrfToken() {
    return messageForm.querySelector('input[name="_token"]').value;
  }

  function openChat() {
    panel.hidden = false;
    launcher.setAttribute('aria-expanded', 'true');
    messageInput.focus();
  }

  function closeChat() {
    panel.hidden = true;
    launcher.setAttribute('aria-expanded', 'false');
    launcher.focus();
  }

  launcher.addEventListener('click', () => panel.hidden ? openChat() : closeChat());
  closeButton.addEventListener('click', closeChat);
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && !panel.hidden) closeChat();
  });

  messageForm.addEventListener('submit', async event => {
    event.preventDefault();
    const question = messageInput.value.trim();
    if (!question) return;

    addMessage(question, 'user');
    messageInput.value = '';
    messageInput.disabled = true;
    sendButton.disabled = true;
    const pending = addMessage('One moment…', 'bot');

    try {
      const response = await fetch(widget.dataset.messageUrl, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken(),
        },
        body: JSON.stringify({ message: question }),
      });
      const result = await response.json();
      pending.remove();
      if (!response.ok) throw new Error(result.message || 'We could not process that question. Please try again.');

      addMessage(result.answer, 'bot', false, result.link_url);
      if (!result.matched) {
        fallback.hidden = false;
        leadMessage.value = question;
        leadMessage.focus();
      }
    } catch (error) {
      pending.remove();
      addMessage(error.message || 'We could not connect right now. Please try again.', 'bot', true);
    } finally {
      messageInput.disabled = false;
      sendButton.disabled = false;
      if (fallback.hidden) messageInput.focus();
    }
  });

  leadForm.addEventListener('submit', async event => {
    event.preventDefault();
    const button = widget.querySelector('[data-chatbot-lead-submit]');
    button.disabled = true;
    const formData = new FormData(leadForm);

    try {
      const response = await fetch(widget.dataset.leadUrl, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken(),
        },
        body: formData,
      });
      const result = await response.json();
      if (!response.ok) {
        const errors = result.errors ? Object.values(result.errors).flat().join(' ') : result.message;
        throw new Error(errors || 'We could not send your details. Please try again.');
      }

      addMessage(result.message, 'bot');
      leadForm.reset();
      fallback.hidden = true;
    } catch (error) {
      addMessage(error.message || 'We could not send your details. Please try again.', 'bot', true);
    } finally {
      button.disabled = false;
    }
  });
})();
