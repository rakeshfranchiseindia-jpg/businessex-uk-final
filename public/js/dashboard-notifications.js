(() => {
  const headerActions = document.querySelector('[data-dashboard-notifications]');
  if (!headerActions) return;

  const activityUrl = headerActions.dataset.activityUrl;
  const csrfToken = headerActions.dataset.csrfToken;
  const messageBadge = headerActions.querySelector('[data-unread-message-count]');
  const notificationBadge = headerActions.querySelector('[data-unread-notification-count]');
  const dropdown = headerActions.querySelector('[data-notification-dropdown]');
  const menuList = headerActions.querySelector('[data-notification-menu-list]');
  const feed = document.querySelector('[data-notification-feed]');
  const markAllButton = headerActions.querySelector('[data-mark-all-notifications]');
  const status = headerActions.querySelector('[data-notification-status]');

  function updateBadge(element, count) {
    if (!element) return;
    const unreadCount = Number(count) || 0;
    element.textContent = unreadCount > 99 ? '99+' : String(unreadCount);
    element.hidden = unreadCount === 0;
  }

  function notificationItem(notification, inMenu) {
    const link = document.createElement('a');
    link.className = inMenu ? 'notification-menu-item' : 'notif-item notification-feed-item';
    if (!notification.read_at) link.classList.add('is-unread');
    link.href = notification.url;
    link.dataset.notificationId = notification.id;
    link.dataset.readUrl = notification.read_url;

    const icon = document.createElement('span');
    const iconColor = notification.type === 'message' ? 'blue' : 'gold';
    icon.className = `notif-icon ${iconColor}`;
    icon.setAttribute('aria-hidden', 'true');
    icon.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>';

    const copy = document.createElement('span');
    copy.className = inMenu ? 'notification-menu-copy' : 'notif-content';

    const title = document.createElement(inMenu ? 'strong' : 'span');
    if (!inMenu) title.className = 'text';
    title.textContent = notification.title;

    const message = document.createElement('span');
    message.textContent = notification.message;
    if (!inMenu) message.className = 'notification-feed-message';

    const time = document.createElement('time');
    time.dateTime = notification.created_at;
    const createdAt = new Date(notification.created_at);
    time.textContent = new Intl.DateTimeFormat(undefined, {
      dateStyle: 'medium',
      timeStyle: 'short',
    }).format(createdAt);
    if (!inMenu) time.className = 'time';

    copy.append(title, message, time);
    link.append(icon, copy);
    return link;
  }

  function renderList(element, notifications, inMenu) {
    if (!element) return;
    element.replaceChildren();
    const items = inMenu ? notifications : notifications.slice(0, 3);
    if (!items.length) {
      const empty = document.createElement('p');
      empty.className = 'notification-empty';
      empty.textContent = inMenu ? 'You are all caught up.' : 'No notifications yet.';
      element.append(empty);
      return;
    }
    items.forEach((notification) => element.append(notificationItem(notification, inMenu)));
  }

  async function refresh() {
    try {
      const response = await fetch(activityUrl, {
        headers: { Accept: 'application/json' },
        credentials: 'same-origin',
      });
      if (!response.ok) throw new Error('Notifications could not be refreshed.');

      const activity = await response.json();
      updateBadge(messageBadge, activity.unreadMessageCount);
      updateBadge(notificationBadge, activity.unreadNotificationCount);
      renderList(menuList, activity.notifications, true);
      renderList(feed, activity.notifications, false);
      markAllButton.hidden = Number(activity.unreadNotificationCount) === 0;
      status.textContent = '';
    } catch (error) {
      status.textContent = error.message || 'Notifications could not be refreshed.';
    }
  }

  async function post(url) {
    const response = await fetch(url, {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'X-Requested-With': 'XMLHttpRequest',
      },
      credentials: 'same-origin',
    });
    if (!response.ok) throw new Error('The notification could not be updated.');
  }

  document.addEventListener('click', async (event) => {
    const notificationLink = event.target.closest('[data-notification-id]');
    if (!notificationLink || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;

    event.preventDefault();
    try {
      await post(notificationLink.dataset.readUrl);
      window.location.assign(notificationLink.href);
    } catch (error) {
      status.textContent = error.message || 'The notification could not be updated.';
    }
  });

  markAllButton.addEventListener('click', async () => {
    try {
      await post(headerActions.dataset.markAllUrl);
      await refresh();
    } catch (error) {
      status.textContent = error.message || 'Notifications could not be updated.';
    }
  });

  if (dropdown) {
    dropdown.addEventListener('toggle', () => {
      if (dropdown.open) refresh();
    });
  }

  refresh();
  window.setInterval(() => {
    if (document.visibilityState === 'visible') refresh();
  }, 20000);
  document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible') refresh();
  });
})();
