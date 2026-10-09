document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-registration-wizard]').forEach(form => {
    const panels = [...form.querySelectorAll('[data-step]')];
    const dots = [...document.querySelectorAll('[data-step-dot]')];
    const indicators = [...document.querySelectorAll('[data-step-indicator]')];
    const stepTitle = document.querySelector('[data-current-step-title]');
    const stepDescription = document.querySelector('[data-current-step-description]');
    const stepLabel = document.querySelector('[data-step-label]');
    let currentStep = 1;

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
      const invalidField = [...panel.querySelectorAll('input, select, textarea')]
        .find(field => !field.checkValidity());
      if (!invalidField) return true;
      invalidField.reportValidity();
      invalidField.focus();
      return false;
    }

    form.querySelectorAll('[data-next-step]').forEach(button => {
      button.addEventListener('click', () => {
        const panel = panels[currentStep - 1];
        if (validatePanel(panel)) showStep(currentStep + 1);
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

    form.addEventListener('submit', event => {
      event.preventDefault();
      for (let index = 0; index < panels.length; index += 1) {
        if (!validatePanel(panels[index])) {
          showStep(index + 1);
          const firstInvalid = [...panels[index].querySelectorAll('input, select, textarea')]
            .find(field => !field.checkValidity());
          firstInvalid?.reportValidity();
          firstInvalid?.focus();
          return;
        }
      }
      HTMLFormElement.prototype.submit.call(form);
    });

    const invalidStep = panels.findIndex(panel => [...panel.querySelectorAll('input, select, textarea')]
      .some(field => [...form.querySelectorAll('[data-registration-error]')]
        .some(error => error.dataset.registrationError === field.name)));
    showStep(invalidStep >= 0 ? invalidStep + 1 : 1);
  });
});
