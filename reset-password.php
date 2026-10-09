<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Choose a new password for your BusinessX account.">
  <title>Reset Password | BusinessX</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/common-style.css?v=20261002">
  <style>
    body { min-height: 100vh; display: flex; flex-direction: column; background: var(--gray-50); font-family: 'Inter', sans-serif; }
    .auth-header { background: var(--navy-900); color: var(--white); }
    .auth-header .container { height: 72px; display: flex; align-items: center; justify-content: space-between; }
    .auth-logo { display: inline-flex; align-items: center; }
    .auth-logo img { display: block; width: 211px; height: 45px; }
    .auth-login { padding: 9px 14px; border: 1px solid rgba(255,255,255,.28); border-radius: 5px; color: var(--white); font-size: 13px; font-weight: 600; }
    .auth-login:hover { color: var(--gold-400); border-color: var(--gold-400); }
    .reset-main { flex: 1; display: grid; place-items: center; padding: 52px 24px; }
    .reset-panel { width: 100%; max-width: 460px; padding: 36px; border: 1px solid var(--gray-200); border-radius: 8px; background: var(--white); box-shadow: 0 8px 30px rgba(10,22,40,.06); }
    .reset-panel h1 { margin-bottom: 8px; color: var(--navy-900); font-size: 25px; line-height: 1.25; font-weight: 700; }
    .reset-panel .intro { margin-bottom: 24px; color: var(--gray-600); font-size: 14px; line-height: 1.6; }
    .reset-form .field { margin-bottom: 16px; }
    .reset-form label { display: block; margin-bottom: 8px; color: var(--gray-800); font-size: 13px; font-weight: 600; }
    .password-wrap { position: relative; }
    .reset-form input { width: 100%; height: 48px; padding: 0 46px 0 13px; border: 1px solid var(--gray-300); border-radius: 5px; background: var(--white); color: var(--gray-900); font-size: 14px; }
    .reset-form input:focus { outline: 2px solid rgba(229,166,35,.2); border-color: var(--gold-500); }
    .password-toggle { position: absolute; top: 0; right: 0; display: grid; place-items: center; width: 44px; height: 48px; color: var(--gray-500); }
    .password-toggle:hover { color: var(--navy-700); }
    .password-toggle svg { width: 18px; height: 18px; }
    .password-hint { margin: -7px 0 16px; color: var(--gray-500); font-size: 11px; line-height: 1.5; }
    .reset-submit { width: 100%; min-height: 48px; margin-top: 4px; padding: 12px 16px; border-radius: 5px; background: var(--gold-500); color: var(--navy-900); font-size: 14px; font-weight: 700; transition: background .18s ease; }
    .reset-submit:hover { background: var(--gold-400); }
    .reset-submit:focus-visible, .auth-login:focus-visible, .return-login a:focus-visible, .password-toggle:focus-visible, .auth-footer a:focus-visible { outline: 2px solid var(--gold-600); outline-offset: 3px; }
    .form-status { margin-top: 14px; padding: 11px 12px; border-left: 3px solid var(--green-500); border-radius: 3px; background: var(--green-100); color: var(--gray-800); font-size: 13px; line-height: 1.5; }
    .form-status.error { border-left-color: var(--red-500); background: #FEF2F2; }
    .form-status[hidden] { display: none; }
    .return-login { display: block; margin-top: 20px; color: var(--gray-600); font-size: 13px; text-align: center; }
    .return-login a { color: var(--gold-600); font-weight: 700; }
    .return-login a:hover { text-decoration: underline; }
    .auth-footer { padding: 18px 0; border-top: 1px solid var(--gray-200); background: var(--white); color: var(--gray-500); font-size: 11px; }
    .auth-footer .container { display: flex; justify-content: space-between; align-items: center; gap: 16px; }
    .auth-footer nav { display: flex; flex-wrap: wrap; gap: 16px; }
    .auth-footer a:hover { color: var(--gold-600); }
    @media (max-width: 560px) {
      .auth-header .container { height: 64px; }
      .auth-logo img { width: 180px; height: auto; }
      .reset-main { padding: 32px 16px; }
      .reset-panel { padding: 26px 22px; }
      .auth-footer .container { flex-direction: column; text-align: center; }
      .auth-footer nav { justify-content: center; gap: 10px 14px; }
    }
  </style>
</head>
<body>
  <header class="auth-header">
    <div class="container">
      <a class="auth-logo" href="index.php" aria-label="BusinessX home"><img src="assets/img/businessx-logo.png" alt="BusinessX"></a>
      <a class="auth-login" href="login.php">Login</a>
    </div>
  </header>

  <main class="reset-main">
    <section class="reset-panel" aria-labelledby="reset-title">
      <h1 id="reset-title">Create a New Password</h1>
      <p class="intro">Choose a strong password you haven't used before.</p>
      <form class="reset-form" id="reset-form">
        <div class="field">
          <label for="new-password">New Password</label>
          <div class="password-wrap">
            <input id="new-password" name="password" type="password" autocomplete="new-password" minlength="8" pattern="(?=.*[A-Za-z])(?=.*[0-9]).{8,}" aria-describedby="password-hint" required>
            <button class="password-toggle" type="button" aria-label="Show new password" aria-pressed="false" data-password-target="new-password"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
          </div>
        </div>
        <p class="password-hint" id="password-hint">Use at least 8 characters, including a letter and a number.</p>
        <div class="field">
          <label for="confirm-password">Confirm New Password</label>
          <div class="password-wrap">
            <input id="confirm-password" name="confirmPassword" type="password" autocomplete="new-password" required>
            <button class="password-toggle" type="button" aria-label="Show confirmation password" aria-pressed="false" data-password-target="confirm-password"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
          </div>
        </div>
        <button class="reset-submit" type="submit">Reset Password</button>
        <p class="form-status" id="reset-status" role="status" aria-live="polite" hidden></p>
      </form>
      <p class="return-login">Remember your password? <a href="login.php">Back to Login</a></p>
    </section>
  </main>

  <?php include __DIR__ . "/includes/footer.php"; ?>

  <script>
    document.querySelectorAll('.password-toggle').forEach(button => {
      button.addEventListener('click', () => {
        const input = document.getElementById(button.dataset.passwordTarget);
        const visible = input.type === 'password';
        input.type = visible ? 'text' : 'password';
        button.setAttribute('aria-pressed', String(visible));
        button.setAttribute('aria-label', `${visible ? 'Hide' : 'Show'} ${button.dataset.passwordTarget === 'new-password' ? 'new password' : 'confirmation password'}`);
      });
    });

    document.getElementById('reset-form').addEventListener('submit', event => {
      event.preventDefault();
      const password = document.getElementById('new-password').value;
      const confirmation = document.getElementById('confirm-password').value;
      const status = document.getElementById('reset-status');
      status.classList.remove('error');
      if (password !== confirmation) {
        status.textContent = 'The passwords do not match. Please try again.';
        status.classList.add('error');
        status.hidden = false;
        document.getElementById('confirm-password').focus();
        return;
      }
      status.textContent = 'Your password has been reset. You can now sign in.';
      status.hidden = false;
      document.querySelector('.reset-submit').disabled = true;
      document.querySelector('.return-login').innerHTML = 'Your account is ready. <a href="login.php">Sign in</a>';
    });
  </script>
</body>
</html>

