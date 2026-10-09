<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Change your BusinessX account password.">
  <title>Change Password | BusinessX</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/common-style.css?v=20261002">
  <link rel="stylesheet" href="css/account-dashboard.css">
  <style>
    .password-field-wrap { position: relative; }
    .password-field-wrap input { padding-right: 46px; }
    .password-toggle { position: absolute; top: 0; right: 0; display: grid; place-items: center; width: 42px; height: 42px; color: var(--gray-500); }
    .password-toggle:hover { color: var(--navy-700); }
    .password-toggle svg { width: 18px; height: 18px; }
    .password-hint { margin-top: 12px; color: var(--gray-500); font-size: 11px; line-height: 1.5; }
    .workspace-status.error { border-left-color: var(--red-500); background: #FEF2F2; }
    .password-toggle:focus-visible { outline: 2px solid var(--gold-500); outline-offset: 2px; }
  </style>
</head>
<body>
  <div class="workspace-shell">
    <aside class="workspace-sidebar">
      <a class="workspace-brand" href="index.php" aria-label="BusinessX home"><img src="assets/img/businessx-logo.png" alt="BusinessX"></a>
      <div class="workspace-user"><strong>Jacob</strong><span>Business account</span></div>
      <nav aria-label="Account navigation">
        <a href="dashboard.php">Dashboard</a>
        <a href="dashboard-profile.php">Edit Profile</a>
        <a href="dashboard-password.php" aria-current="page">Change Password</a>
        <span class="workspace-nav-heading">My Interaction</span>
        <a href="bx-inbox.php">BX Inbox</a>
        <a href="proposal-sent.php">Proposal sent</a>
        <a href="proposal-received.php">Proposal received</a>
        <a href="instant-response.php">Instant Response</a>
      </nav>
    </aside>

    <div class="workspace-main">
      <header class="workspace-topbar">
        <strong>My Account / Change Password</strong>
        <a href="login.php">Sign Out</a>
      </header>
      <main class="workspace-content">
        <div class="page-title-row">
          <div>
            <h1>Change Password</h1>
            <p>Keep your account secure with a strong password.</p>
          </div>
          <a class="account-primary-action" href="dashboard.php">Back to Dashboard</a>
        </div>

        <section class="workspace-panel" aria-labelledby="password-form-title">
          <h2 id="password-form-title">Update Password</h2>
          <form id="password-form">
            <div class="workspace-form-grid">
              <div class="workspace-field full">
                <label for="old-password">Old Password *</label>
                <div class="password-field-wrap">
                  <input id="old-password" name="oldPassword" type="password" autocomplete="current-password" required>
                  <button class="password-toggle" type="button" aria-label="Show old password" aria-pressed="false" data-password-target="old-password"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                </div>
              </div>
              <div class="workspace-field">
                <label for="new-password">New Password *</label>
                <div class="password-field-wrap">
                  <input id="new-password" name="newPassword" type="password" autocomplete="new-password" minlength="8" pattern="(?=.*[A-Za-z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,}" aria-describedby="password-hint" required>
                  <button class="password-toggle" type="button" aria-label="Show new password" aria-pressed="false" data-password-target="new-password"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                </div>
              </div>
              <div class="workspace-field">
                <label for="confirm-password">Confirm Password *</label>
                <div class="password-field-wrap">
                  <input id="confirm-password" name="confirmPassword" type="password" autocomplete="new-password" required>
                  <button class="password-toggle" type="button" aria-label="Show confirmation password" aria-pressed="false" data-password-target="confirm-password"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                </div>
              </div>
            </div>
            <p class="password-hint" id="password-hint">Use at least 8 characters, including a letter, a number, and a symbol.</p>
            <button class="workspace-submit" type="submit">Update Password</button>
            <p class="workspace-status" id="password-status" role="status" aria-live="polite" hidden></p>
          </form>
        </section>
      </main>
    </div>
  </div>

  <?php include __DIR__ . "/includes/footer.php"; ?>

  <script>
    document.querySelectorAll('.password-toggle').forEach(button => {
      button.addEventListener('click', () => {
        const input = document.getElementById(button.dataset.passwordTarget);
        const visible = input.type === 'password';
        input.type = visible ? 'text' : 'password';
        button.setAttribute('aria-pressed', String(visible));
        button.setAttribute('aria-label', `${visible ? 'Hide' : 'Show'} ${button.dataset.passwordTarget.replace('-', ' ')}`);
      });
    });

    document.getElementById('password-form').addEventListener('submit', event => {
      event.preventDefault();
      const form = event.currentTarget;
      const newPassword = document.getElementById('new-password');
      const confirmation = document.getElementById('confirm-password');
      const status = document.getElementById('password-status');
      status.classList.remove('error');
      if (newPassword.value !== confirmation.value) {
        status.textContent = 'The new passwords do not match.';
        status.classList.add('error');
        status.hidden = false;
        confirmation.focus();
        return;
      }
      status.textContent = 'Password form validated. This local mockup is not connected to the live account.';
      status.hidden = false;
      form.reset();
    });
  </script>
</body>
</html>

