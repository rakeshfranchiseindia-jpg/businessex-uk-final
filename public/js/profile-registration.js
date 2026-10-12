document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-registration-wizard]').forEach(form => {
    const panels = [...form.querySelectorAll('[data-step]')];
    const dots = [...document.querySelectorAll('[data-step-dot]')];
    const indicators = [...document.querySelectorAll('[data-step-indicator]')];
    const stepTitle = document.querySelector('[data-current-step-title]');
    const stepDescription = document.querySelector('[data-current-step-description]');
    const stepLabel = document.querySelector('[data-step-label]');
    let currentStep = 1;

    // "I am looking for" checkboxes (Requirements step) reveal/hide their
    // own related fields, same as the old project.
    form.querySelectorAll('.requirement-checkbox').forEach(checkbox => {
      checkbox.addEventListener('change', () => {
        const exclusiveFieldset = checkbox.closest('[data-exclusive-group]');
        if (exclusiveFieldset && checkbox.checked) {
          exclusiveFieldset.querySelectorAll('.requirement-checkbox').forEach(sibling => {
            if (sibling !== checkbox && sibling.checked) {
              sibling.checked = false;
              sibling.dispatchEvent(new Event('change', { bubbles: true }));
            }
          });
        }

        const group = checkbox.dataset.requirementTarget;
        form.querySelectorAll(`[data-requirement-group="${group}"]`).forEach(field => {
          field.style.display = checkbox.checked ? '' : 'none';
        });
      });
    });

    // File fields carry data-max-size-mb (see registration-profile-wizard.blade.php) —
    // reject an oversized file client-side via the native validity API, so
    // validatePanel()'s checkValidity() picks it up like any other field.
    form.querySelectorAll('input[type="file"][data-max-size-mb]').forEach(input => {
      input.addEventListener('change', () => {
        const maxBytes = Number(input.dataset.maxSizeMb) * 1024 * 1024;
        const oversized = [...input.files].find(file => file.size > maxBytes);
        if (oversized) {
          input.setCustomValidity(`"${oversized.name}" is ${(oversized.size / 1024 / 1024).toFixed(2)}MB — files must be ${input.dataset.maxSizeMb}MB or smaller.`);
          input.reportValidity();
          input.value = '';
        } else {
          input.setCustomValidity('');
        }
      });
    });

    function showStep(stepNumber) {
      currentStep = Math.max(1, Math.min(stepNumber, panels.length));
      const activePanel = panels[currentStep - 1];

      panels.forEach((panel, index) => {
        panel.hidden = index + 1 !== currentStep;
      });
      dots.forEach((dot, index) => {
        dot.classList.toggle('active', index < currentStep);
        dot.classList.toggle('done', index + 1 < currentStep);
        dot.classList.toggle('current', index + 1 === currentStep);
      });
      indicators.forEach((indicator, index) => {
        indicator.classList.toggle('active', index + 1 === currentStep);
        indicator.classList.toggle('done', index + 1 < currentStep);
      });
      if (stepTitle) stepTitle.textContent = activePanel.querySelector('.registration-step-heading h3').textContent;
      if (stepDescription) stepDescription.textContent = activePanel.querySelector('.registration-step-heading p').textContent;
      if (stepLabel) stepLabel.textContent = `Step ${currentStep} of ${panels.length}`;

      const previousButton = activePanel.querySelector('[data-prev-step]');
      if (previousButton) previousButton.hidden = currentStep === 1;
    }

    function validatePanel(panel) {
      const fields = [...panel.querySelectorAll('input, select, textarea')];
      fields.forEach(field => field.classList.remove('has-error'));

      const invalidField = fields.find(field => !field.checkValidity());
      if (!invalidField) return true;

      invalidField.classList.add('has-error');
      invalidField.addEventListener('input', () => invalidField.classList.remove('has-error'), { once: true });
      invalidField.reportValidity();
      invalidField.focus();
      return false;
    }

    let isNavigating = false;

    form.querySelectorAll('[data-next-step]').forEach(button => {
      button.addEventListener('click', async () => {
        // Guard against a double-click firing two overlapping checks —
        // each one hits /uk-cities/suggest, and the button must stay put
        // (not sneak forward) on the first invalid field either way.
        if (isNavigating) return;
        isNavigating = true;
        button.disabled = true;
        try {
          const panel = panels[currentStep - 1];
          // Required fields first — no need to round-trip to the city
          // endpoint if a plain required field is already empty.
          if (!validatePanel(panel)) return;
          const cityValid = await window.BX?.validateCityInputsIn?.(panel) ?? true;
          if (cityValid) showStep(currentStep + 1);
        } finally {
          isNavigating = false;
          button.disabled = false;
        }
      });
    });

    form.querySelectorAll('[data-prev-step]').forEach(button => {
      button.addEventListener('click', () => showStep(currentStep - 1));
    });

    form.querySelectorAll('[data-cancel-wizard]').forEach(button => {
      button.addEventListener('click', () => {
        window.location.href = form.dataset.cancelUrl;
      });
    });

    form.addEventListener('submit', async event => {
      event.preventDefault();
      if (isNavigating) return;
      isNavigating = true;
      try {
        for (let index = 0; index < panels.length; index += 1) {
          if (!validatePanel(panels[index])) {
            showStep(index + 1);
            const firstInvalid = [...panels[index].querySelectorAll('input, select, textarea')]
              .find(field => !field.checkValidity());
            firstInvalid?.reportValidity();
            firstInvalid?.focus();
            return;
          }
          const cityValid = await window.BX?.validateCityInputsIn?.(panels[index]) ?? true;
          if (!cityValid) {
            showStep(index + 1);
            return;
          }
        }
        HTMLFormElement.prototype.submit.call(form);
      } finally {
        isNavigating = false;
      }
    });

    const invalidStep = panels.findIndex(panel => [...panel.querySelectorAll('input, select, textarea')]
      .some(field => [...form.querySelectorAll('[data-registration-error]')]
        .some(error => error.dataset.registrationError === field.name)));
    showStep(invalidStep >= 0 ? invalidStep + 1 : 1);
  });
});
