const profileTabs = [...document.querySelectorAll('[data-profile-tab]')];
const profilePanels = [...document.querySelectorAll('[data-profile-panel]')];

function activateProfileTab(tab) {
  profileTabs.forEach(item => {
    const selected = item === tab;
    item.setAttribute('aria-selected', String(selected));
    item.tabIndex = selected ? 0 : -1;
  });

  profilePanels.forEach(panel => {
    panel.hidden = panel.dataset.profilePanel !== tab.dataset.profileTab;
  });
}

profileTabs.forEach((tab, index) => {
  tab.addEventListener('click', () => activateProfileTab(tab));
  tab.addEventListener('keydown', event => {
    const next = event.key === 'ArrowRight' ? (index + 1) % profileTabs.length
      : event.key === 'ArrowLeft' ? (index - 1 + profileTabs.length) % profileTabs.length
      : event.key === 'Home' ? 0
      : event.key === 'End' ? profileTabs.length - 1
      : -1;

    if (next >= 0) {
      event.preventDefault();
      profileTabs[next].focus();
      activateProfileTab(profileTabs[next]);
    }
  });
});

const dashboardScreens = [...document.querySelectorAll('[data-dashboard-screen]')];
const manageMenu = document.querySelector('[data-manage-menu]');
const dashboardMenu = document.querySelector('.account-navigation a[href="dashboard.php"]');

function showDashboardScreen(screenName) {
  dashboardScreens.forEach(screen => {
    screen.hidden = screen.dataset.dashboardScreen !== screenName;
  });

  const manageIsOpen = screenName === 'manage';
  manageMenu.classList.toggle('active', manageIsOpen);
  dashboardMenu.classList.toggle('active', !manageIsOpen);

  if (manageIsOpen) {
    manageMenu.setAttribute('aria-current', 'page');
    dashboardMenu.removeAttribute('aria-current');
  } else {
    dashboardMenu.setAttribute('aria-current', 'page');
    manageMenu.removeAttribute('aria-current');
  }
}

manageMenu?.addEventListener('click', event => {
  event.preventDefault();
  showDashboardScreen('manage');
});

document.querySelector('[data-manage-back]')?.addEventListener('click', () => {
  showDashboardScreen('overview');
});

const manageTabs = [...document.querySelectorAll('[data-manage-tab]')];
const managePanels = [...document.querySelectorAll('[data-manage-panel]')];

function activateManageTab(tab) {
  manageTabs.forEach(item => {
    const selected = item === tab;
    item.setAttribute('aria-selected', String(selected));
    item.tabIndex = selected ? 0 : -1;
  });

  managePanels.forEach(panel => {
    panel.hidden = panel.dataset.managePanel !== tab.dataset.manageTab;
  });
}

manageTabs.forEach((tab, index) => {
  tab.addEventListener('click', () => activateManageTab(tab));
  tab.addEventListener('keydown', event => {
    const next = event.key === 'ArrowRight' ? (index + 1) % manageTabs.length
      : event.key === 'ArrowLeft' ? (index - 1 + manageTabs.length) % manageTabs.length
      : event.key === 'Home' ? 0
      : event.key === 'End' ? manageTabs.length - 1
      : -1;

    if (next >= 0) {
      event.preventDefault();
      manageTabs[next].focus();
      activateManageTab(manageTabs[next]);
    }
  });
});

const manageForm = document.getElementById('manage-business-form');
const manageStorageKey = 'businessX.manageBusinessInformation';

if (manageForm) {
  try {
    const savedFields = JSON.parse(localStorage.getItem(manageStorageKey) || '{}');
    Object.entries(savedFields).forEach(([name, value]) => {
      const field = manageForm.elements.namedItem(name);
      if (field && field.type !== 'file') field.value = value;
    });
  } catch {
    localStorage.removeItem(manageStorageKey);
  }

  manageForm.addEventListener('submit', event => {
    event.preventDefault();
    const activePanel = manageForm.querySelector('[data-manage-panel]:not([hidden])');
    const requiredFields = [...activePanel.querySelectorAll('[required]')];
    const invalidField = requiredFields.find(field => !field.reportValidity());

    if (invalidField) return;

    const savedFields = {};
    [...manageForm.elements].forEach(field => {
      if (field.name && field.type !== 'file') savedFields[field.name] = field.value;
    });
    localStorage.setItem(manageStorageKey, JSON.stringify(savedFields));

    const status = document.getElementById('manage-form-status');
    const activeTab = manageForm.querySelector('[data-manage-tab][aria-selected="true"]');
    status.textContent = `${activeTab.textContent} saved in this browser.`;
  });
}
