// BusinessX - World Trade Council | Shared Interactions

document.addEventListener('DOMContentLoaded', () => {

  // === UK city and location suggestions ===
  const locationInputs = [...document.querySelectorAll('input:not([type]), input[type="text"]')]
    .filter(input => {
      const searchableAttributes = [
        input.name,
        input.id,
        input.getAttribute('placeholder'),
        input.getAttribute('aria-label'),
      ].filter(Boolean).join(' ').toLowerCase();

      return /\b(city|location|town)\b|_(city|location|town)\b/.test(searchableAttributes);
    });

  locationInputs.forEach((input, inputIndex) => {
    if (input.closest('.city-autocomplete')) return;

    const wrapper = document.createElement('div');
    wrapper.className = 'city-autocomplete';
    input.parentNode.insertBefore(wrapper, input);
    wrapper.appendChild(input);

    const listbox = document.createElement('div');
    listbox.className = 'city-suggestions';
    listbox.id = `city-suggestions-${inputIndex}`;
    listbox.setAttribute('role', 'listbox');
    listbox.hidden = true;
    wrapper.appendChild(listbox);

    input.setAttribute('aria-autocomplete', 'list');
    input.setAttribute('aria-controls', listbox.id);
    input.setAttribute('aria-expanded', 'false');

    let debounceTimer;
    let requestController;
    let activeIndex = -1;

    const hideSuggestions = () => {
      listbox.hidden = true;
      listbox.replaceChildren();
      input.setAttribute('aria-expanded', 'false');
      input.removeAttribute('aria-activedescendant');
      activeIndex = -1;
    };

    const setActiveOption = index => {
      const options = [...listbox.querySelectorAll('[role="option"]')];
      if (!options.length) return;
      activeIndex = (index + options.length) % options.length;
      options.forEach((option, optionIndex) => {
        option.setAttribute('aria-selected', String(optionIndex === activeIndex));
      });
      input.setAttribute('aria-activedescendant', options[activeIndex].id);
    };

    const selectOption = option => {
      input.value = option.textContent;
      hideSuggestions();
      input.dispatchEvent(new Event('change', { bubbles: true }));
    };

    input.addEventListener('input', () => {
      clearTimeout(debounceTimer);
      if (requestController) requestController.abort();

      const query = input.value.trim();
      if (query.length < 2) {
        hideSuggestions();
        return;
      }

      debounceTimer = setTimeout(async () => {
        requestController = new AbortController();
        try {
          const response = await fetch(`/uk-cities/suggest?q=${encodeURIComponent(query)}`, {
            headers: { Accept: 'application/json' },
            signal: requestController.signal,
          });
          if (!response.ok) throw new Error(`City suggestions request failed (${response.status}).`);

          const result = await response.json();
          if (input.value.trim() !== query) return;

          listbox.replaceChildren();
          result.cities.forEach((city, cityIndex) => {
            const option = document.createElement('div');
            option.className = 'city-suggestion-option';
            option.id = `${listbox.id}-option-${cityIndex}`;
            option.setAttribute('role', 'option');
            option.setAttribute('aria-selected', 'false');
            option.textContent = city;
            option.addEventListener('pointerdown', event => {
              event.preventDefault();
              selectOption(option);
            });
            listbox.appendChild(option);
          });

          listbox.hidden = result.cities.length === 0;
          input.setAttribute('aria-expanded', String(!listbox.hidden));
          activeIndex = -1;
        } catch (error) {
          if (error.name === 'AbortError') return;
          console.error(error);
          listbox.replaceChildren();
          const status = document.createElement('div');
          status.className = 'city-suggestion-status';
          status.setAttribute('role', 'status');
          status.textContent = 'City suggestions could not be loaded.';
          listbox.appendChild(status);
          listbox.hidden = false;
          input.setAttribute('aria-expanded', 'true');
        }
      }, 180);
    });

    input.addEventListener('keydown', event => {
      if (listbox.hidden) return;
      if (event.key === 'ArrowDown') {
        event.preventDefault();
        setActiveOption(activeIndex + 1);
      } else if (event.key === 'ArrowUp') {
        event.preventDefault();
        setActiveOption(activeIndex < 0 ? 0 : activeIndex - 1);
      } else if (event.key === 'Enter' && activeIndex >= 0) {
        event.preventDefault();
        const option = listbox.querySelectorAll('[role="option"]')[activeIndex];
        if (option) selectOption(option);
      } else if (event.key === 'Escape') {
        hideSuggestions();
      }
    });

    input.addEventListener('blur', () => {
      window.setTimeout(hideSuggestions, 120);
    });
  });

  // === International phone country codes ===
  const phoneGroups = document.querySelectorAll('.phone-input-group');
  phoneGroups.forEach(group => {
    const codeSelect = group.querySelector('[data-phone-country-code]');
    const phoneInput = group.querySelector('input[type="tel"]');
    if (!codeSelect || !phoneInput) return;

    const matchingOption = value => Array.from(codeSelect.options)
      .filter(option => value.startsWith(option.value))
      .sort((first, second) => second.value.length - first.value.length)[0];

    if (phoneInput.value.trim().startsWith('+')) {
      const match = matchingOption(phoneInput.value.trim());
      if (match) {
        codeSelect.value = match.value;
        phoneInput.value = phoneInput.value.trim().slice(match.value.length).replace(/\D/g, '');
      }
    }
  });

  document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', () => {
      form.querySelectorAll('.phone-input-group').forEach(group => {
        const codeSelect = group.querySelector('[data-phone-country-code]');
        const phoneInput = group.querySelector('input[type="tel"]');
        if (!codeSelect || !phoneInput || !phoneInput.value.trim()) return;

        let value = phoneInput.value.trim();
        let countryCode = codeSelect.value;
        if (value.startsWith('+')) {
          const match = Array.from(codeSelect.options)
            .filter(option => value.startsWith(option.value))
            .sort((first, second) => second.value.length - first.value.length)[0];
          if (!match) {
            phoneInput.value = value.replace(/\s/g, '');
            return;
          }
          countryCode = match.value;
          codeSelect.value = match.value;
          value = value.slice(match.value.length);
        }

        let nationalNumber = value.replace(/\D/g, '');
        if (countryCode === '+44' && nationalNumber.startsWith('0')) {
          nationalNumber = nationalNumber.slice(1);
        }
        phoneInput.value = `${countryCode}${nationalNumber}`;
      });
    }, true);
  });

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
