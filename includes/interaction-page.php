<?php
$interactionPages = [
    'inbox' => [
        'title' => 'BX Inbox',
        'description' => 'View and manage conversations with businesses in your network.',
        'emptyTitle' => 'Your inbox is clear',
        'emptyDescription' => 'New messages from your business connections will appear here.',
    ],
    'sent' => [
        'title' => 'Proposal sent',
        'description' => 'Keep track of proposals you have sent to other businesses.',
        'emptyTitle' => 'No proposals sent yet',
        'emptyDescription' => 'Proposals you send to businesses will be listed here with their current status.',
    ],
    'received' => [
        'title' => 'Proposal received',
        'description' => 'Review and respond to proposals received from other businesses.',
        'emptyTitle' => 'No proposals received',
        'emptyDescription' => 'When a business sends you a proposal, you can review it here.',
    ],
    'instant-response' => [
        'title' => 'Instant Response',
        'description' => 'Set an automatic reply for new messages when you are unavailable.',
        'emptyTitle' => '',
        'emptyDescription' => '',
    ],
];

$interactionPage = $interactionPages[$interactionPageKey] ?? $interactionPages['inbox'];
$interactionLinks = [
    'inbox' => ['label' => 'BX Inbox', 'href' => 'bx-inbox.php'],
    'sent' => ['label' => 'Proposal sent', 'href' => 'proposal-sent.php'],
    'received' => ['label' => 'Proposal received', 'href' => 'proposal-received.php'],
    'instant-response' => ['label' => 'Instant Response', 'href' => 'instant-response.php'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Manage your BusinessX interactions.">
  <title><?= htmlspecialchars($interactionPage['title'], ENT_QUOTES, 'UTF-8') ?> | BusinessX</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/common-style.css?v=20261002">
  <link rel="stylesheet" href="css/account-dashboard.css">
</head>
<body>
  <div class="workspace-shell">
    <aside class="workspace-sidebar">
      <a class="workspace-brand" href="index.php" aria-label="BusinessX home"><img src="assets/img/businessx-logo.png?v=20261008" alt="BusinessX"></a>
      <div class="workspace-user"><strong>Jacob</strong><span>Business account</span></div>
      <nav aria-label="Account navigation">
        <a href="dashboard.php">Dashboard</a>
        <a href="dashboard-profile.php">Edit Profile</a>
        <a href="dashboard-password.php">Change Password</a>
        <span class="workspace-nav-heading">My Interaction</span>
        <?php foreach ($interactionLinks as $key => $link): ?>
          <a href="<?= htmlspecialchars($link['href'], ENT_QUOTES, 'UTF-8') ?>"<?= $key === $interactionPageKey ? ' aria-current="page"' : '' ?>><?= htmlspecialchars($link['label'], ENT_QUOTES, 'UTF-8') ?></a>
        <?php endforeach; ?>
      </nav>
    </aside>

    <div class="workspace-main">
      <header class="workspace-topbar">
        <strong>My Account / My Interaction / <?= htmlspecialchars($interactionPage['title'], ENT_QUOTES, 'UTF-8') ?></strong>
        <a href="login.php">Sign Out</a>
      </header>
      <main class="workspace-content">
        <div class="page-title-row">
          <div>
            <h1><?= htmlspecialchars($interactionPage['title'], ENT_QUOTES, 'UTF-8') ?></h1>
            <p><?= htmlspecialchars($interactionPage['description'], ENT_QUOTES, 'UTF-8') ?></p>
          </div>
          <a class="account-primary-action" href="dashboard.php">Back to Dashboard</a>
        </div>

        <?php if ($interactionPageKey === 'instant-response'): ?>
          <section class="workspace-panel" aria-labelledby="instant-response-title">
            <h2 id="instant-response-title">Automatic reply</h2>
            <form id="instant-response-form">
              <label class="interaction-toggle-row" for="instant-response-enabled">
                <span><strong>Enable instant response</strong><span>Automatically reply to new messages.</span></span>
                <input id="instant-response-enabled" name="enabled" type="checkbox">
              </label>
              <div class="workspace-field" style="margin-top:18px;">
                <label for="instant-response-message">Response message</label>
                <textarea class="interaction-textarea" id="instant-response-message" name="message" rows="5" maxlength="500">Thank you for your message. We have received it and will get back to you as soon as possible.</textarea>
              </div>
              <button class="workspace-submit" type="submit">Save response</button>
              <p class="workspace-status interaction-status" id="instant-response-status" role="status" aria-live="polite" hidden></p>
            </form>
          </section>
        <?php elseif ($interactionPageKey === 'sent' || $interactionPageKey === 'received'): ?>
          <section class="workspace-panel" aria-labelledby="proposal-list-title">
            <h2 id="proposal-list-title">Proposal activity</h2>
            <div class="interaction-table-wrap">
              <table class="interaction-table">
                <thead><tr><th>Business</th><th>Proposal</th><th>Date</th><th>Status</th></tr></thead>
                <tbody><tr><td colspan="4"><?= htmlspecialchars($interactionPage['emptyTitle'], ENT_QUOTES, 'UTF-8') ?>. <?= htmlspecialchars($interactionPage['emptyDescription'], ENT_QUOTES, 'UTF-8') ?></td></tr></tbody>
              </table>
            </div>
          </section>
        <?php else: ?>
          <section class="workspace-panel interaction-empty" aria-labelledby="inbox-empty-title">
            <span class="empty-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="m3 7 9 6 9-6"/></svg></span>
            <h2 id="inbox-empty-title"><?= htmlspecialchars($interactionPage['emptyTitle'], ENT_QUOTES, 'UTF-8') ?></h2>
            <p><?= htmlspecialchars($interactionPage['emptyDescription'], ENT_QUOTES, 'UTF-8') ?></p>
          </section>
        <?php endif; ?>
      </main>
    </div>
  </div>

  <?php include __DIR__ . '/footer.php'; ?>

  <?php if ($interactionPageKey === 'instant-response'): ?>
  <script>
    const responseForm = document.getElementById('instant-response-form');
    const responseKey = 'businessX.instantResponse';
    try {
      const savedResponse = JSON.parse(localStorage.getItem(responseKey) || '{}');
      document.getElementById('instant-response-enabled').checked = Boolean(savedResponse.enabled);
      if (savedResponse.message) document.getElementById('instant-response-message').value = savedResponse.message;
    } catch {
      localStorage.removeItem(responseKey);
    }
    responseForm.addEventListener('submit', event => {
      event.preventDefault();
      const data = new FormData(responseForm);
      localStorage.setItem(responseKey, JSON.stringify({
        enabled: data.has('enabled'),
        message: data.get('message')
      }));
      const status = document.getElementById('instant-response-status');
      status.textContent = 'Your instant response settings were saved in this browser.';
      status.hidden = false;
    });
  </script>
  <?php endif; ?>
</body>
</html>