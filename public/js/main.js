// BusinessX - World Trade Council | Shared Interactions

document.addEventListener('DOMContentLoaded', () => {

  // === Mobile menu toggle ===
  const mobileToggle = document.querySelector('.mobile-toggle');
  const mobileMenu = document.querySelector('.mobile-menu');
  if (mobileToggle && mobileMenu) {
    mobileToggle.addEventListener('click', () => {
      mobileMenu.classList.toggle('open');
    });
  }

  // === Mobile menu sub-groups ===
  document.querySelectorAll('.mm-group-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      btn.closest('.mm-group').classList.toggle('open');
    });
  });

  // === Accordion (FAQ) ===
  document.querySelectorAll('[data-accordion]').forEach(acc => {
    const trigger = acc.querySelector('[data-accordion-trigger]');
    const panel = acc.querySelector('[data-accordion-panel]');
    const icon = acc.querySelector('[data-accordion-icon]');
    if (!trigger || !panel) return;
    trigger.addEventListener('click', () => {
      const isOpen = acc.classList.toggle('open');
      panel.style.maxHeight = isOpen ? panel.scrollHeight + 'px' : '0';
      if (icon) icon.style.transform = isOpen ? 'rotate(45deg)' : 'rotate(0)';
    });
  });

  // === Pricing toggle (monthly/annual) ===
  const pricingToggle = document.querySelector('[data-pricing-toggle]');
  if (pricingToggle) {
    pricingToggle.addEventListener('change', e => {
      document.querySelectorAll('[data-price-monthly]').forEach(el => {
        const monthly = el.dataset.priceMonthly;
        const annual = el.dataset.priceAnnual;
        el.textContent = e.target.checked ? annual : monthly;
      });
      document.querySelectorAll('[data-billing-period]').forEach(el => {
        el.textContent = e.target.checked ? '/year' : '/month';
      });
    });
  }

  // === Tabs ===
  document.querySelectorAll('[data-tabs]').forEach(tabGroup => {
    const triggers = tabGroup.querySelectorAll('[data-tab-trigger]');
    triggers.forEach(t => {
      t.addEventListener('click', () => {
        const id = t.dataset.tabTrigger;
        triggers.forEach(x => x.classList.remove('active'));
        t.classList.add('active');
        tabGroup.querySelectorAll('[data-tab-panel]').forEach(p => {
          p.classList.toggle('active', p.dataset.tabPanel === id);
        });
      });
    });
  });

  // === Form steps (registration) ===
  const stepForms = document.querySelectorAll('[data-step]');
  if (stepForms.length) {
    const totalSteps = stepForms.length;
    const showStep = (n) => {
      stepForms.forEach((s, i) => {
        s.style.display = (i + 1 === n) ? 'block' : 'none';
      });
      const progress = document.querySelector('[data-step-progress]');
      if (progress) progress.style.width = `${(n/totalSteps)*100}%`;
      document.querySelectorAll('[data-step-dot]').forEach((d, i) => {
        d.classList.toggle('active', i < n);
        d.classList.toggle('current', i === n - 1);
      });
    };
    showStep(1);
    document.querySelectorAll('[data-next-step]').forEach(btn => {
      btn.addEventListener('click', () => {
        const current = parseInt(btn.closest('[data-step]').dataset.step);
        if (current < totalSteps) showStep(current + 1);
      });
    });
    document.querySelectorAll('[data-prev-step]').forEach(btn => {
      btn.addEventListener('click', () => {
        const current = parseInt(btn.closest('[data-step]').dataset.step);
        if (current > 1) showStep(current - 1);
      });
    });
  }

  // === Password show toggle ===
  document.querySelectorAll('[data-toggle-password]').forEach(btn => {
    btn.addEventListener('click', () => {
      const input = document.querySelector(btn.dataset.togglePassword);
      if (input) {
        const isPw = input.type === 'password';
        input.type = isPw ? 'text' : 'password';
        btn.innerHTML = isPw
          ? '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>'
          : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
      }
    });
  });
});
