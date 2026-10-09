<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Edit your BusinessX account profile.">
  <title>Edit Profile | BusinessX</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/common-style.css?v=20261002">
  <link rel="stylesheet" href="css/account-dashboard.css">
</head>
<body>
  <div class="workspace-shell">
    <aside class="workspace-sidebar">
      <a class="workspace-brand" href="index.php" aria-label="BusinessX home"><img src="assets/img/businessx-logo.png" alt="BusinessX"></a>
      <div class="workspace-user"><strong>Jacob</strong><span>Business account</span></div>
      <nav aria-label="Account navigation">
        <a href="dashboard.php">Dashboard</a>
        <a href="dashboard-profile.php" aria-current="page">Edit Profile</a>
        <a href="dashboard-password.php">Change Password</a>
        <span class="workspace-nav-heading">My Interaction</span>
        <a href="bx-inbox.php">BX Inbox</a>
        <a href="proposal-sent.php">Proposal sent</a>
        <a href="proposal-received.php">Proposal received</a>
        <a href="instant-response.php">Instant Response</a>
      </nav>
    </aside>

    <div class="workspace-main">
      <header class="workspace-topbar">
        <strong>My Account / Edit Profile</strong>
        <a href="login.php">Sign Out</a>
      </header>
      <main class="workspace-content">
        <div class="page-title-row">
          <div>
            <h1>Edit Profile</h1>
            <p>Update the contact details associated with your account.</p>
          </div>
          <a class="account-primary-action" href="dashboard.php">Back to Dashboard</a>
        </div>

        <section class="workspace-panel" aria-labelledby="profile-form-title">
          <h2 id="profile-form-title">Personal Information</h2>
          <form id="profile-form">
            <div class="workspace-form-grid">
              <div class="workspace-field">
                <label for="profile-name">Name *</label>
                <input id="profile-name" name="name" autocomplete="name" value="Jacob" required>
              </div>
              <div class="workspace-field">
                <label for="profile-email">Email *</label>
                <input id="profile-email" name="email" type="email" autocomplete="email" required>
              </div>
              <div class="workspace-field">
                <label for="profile-phone">Mobile *</label>
                <div class="phone-input-group"><?php include __DIR__ . '/includes/phone-country-code.php'; ?><input id="profile-phone" name="phone" type="tel" autocomplete="tel" required></div>
              </div>
              <div class="workspace-field">
                <label for="profile-location">Location *</label>
                <input id="profile-location" name="location" autocomplete="address-level2" required>
              </div>
              <div class="workspace-field">
                <label for="profile-designation">Designation *</label>
                <input id="profile-designation" name="designation" required>
              </div>
              <div class="workspace-field">
                <label for="profile-company">Company *</label>
                <input id="profile-company" name="company" value="Business account" autocomplete="organization" required>
              </div>
            </div>
            <button class="workspace-submit" type="submit">Save Changes</button>
            <p class="workspace-status" id="profile-status" role="status" aria-live="polite" hidden></p>
          </form>
        </section>
      </main>
    </div>
  </div>

  <?php include __DIR__ . "/includes/footer.php"; ?>

  <script>
    const profileStorageKey = 'businessX.accountProfile';
    const profileForm = document.getElementById('profile-form');
    let savedProfile = {};
    try {
      savedProfile = JSON.parse(localStorage.getItem(profileStorageKey) || '{}');
    } catch {
      savedProfile = {};
    }
    Object.entries(savedProfile).forEach(([name, value]) => {
      if (profileForm.elements[name] && value) profileForm.elements[name].value = value;
    });

    profileForm.addEventListener('submit', event => {
      event.preventDefault();
      const profile = Object.fromEntries(new FormData(profileForm).entries());
      localStorage.setItem(profileStorageKey, JSON.stringify(profile));
      const status = document.getElementById('profile-status');
      status.textContent = 'Profile changes saved in this browser.';
      status.hidden = false;
    });
  </script>
</body>
</html>
