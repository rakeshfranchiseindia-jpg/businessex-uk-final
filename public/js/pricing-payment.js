document.addEventListener('DOMContentLoaded', () => {
  const buttons = document.querySelectorAll('[data-pay-plan]');
  if (!buttons.length || typeof Razorpay === 'undefined') return;

  const statusEl = document.getElementById('pricing-payment-status');
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

  function showStatus(message, kind) {
    if (!statusEl) return;
    statusEl.textContent = message;
    statusEl.style.display = 'block';
    statusEl.style.background = kind === 'error' ? '#fef2f2' : '#ecfdf5';
    statusEl.style.color = kind === 'error' ? '#991b1b' : '#166534';
  }

  function collectFormData() {
    const name = document.getElementById('pricing-name')?.value.trim() || '';
    const mobile = document.getElementById('pricing-mobile')?.value.trim() || '';
    const email = document.getElementById('pricing-email')?.value.trim() || '';
    const company = document.getElementById('pricing-company')?.value.trim() || '';
    const paymentMode = document.getElementById('pricing-payment-mode')?.value || '';
    const profileType = document.querySelector('input[name="pricing-profile-type"]:checked')?.value || '';
    const billingToggle = document.querySelector('[data-pricing-toggle]');
    const billingPeriod = billingToggle?.checked ? 'annual' : 'monthly';

    return { name, mobile, email, company, payment_mode: paymentMode, profile_type: profileType, billing_period: billingPeriod };
  }

  function clearFieldErrors() {
    document.querySelectorAll('.plan-selection-field .field-error').forEach(el => {
      el.textContent = '';
      el.classList.remove('is-visible');
    });
    document.querySelectorAll('.plan-selection-field .has-error').forEach(el => {
      el.classList.remove('has-error');
    });
  }

  function showFieldError(fieldId, message) {
    const field = document.getElementById(fieldId);
    const errorEl = document.querySelector(`.field-error[data-error-for="${fieldId}"]`);
    field?.classList.add('has-error');
    if (errorEl) {
      errorEl.textContent = message;
      errorEl.classList.add('is-visible');
    }
  }

  function validate(data) {
    clearFieldErrors();
    const errors = {};
    const nameField = document.getElementById('pricing-name');
    const companyField = document.getElementById('pricing-company');

    if (!data.name) {
      errors['pricing-name'] = 'Please enter your name.';
    } else if (nameField && !nameField.checkValidity()) {
      errors['pricing-name'] = 'Name should only contain letters and spaces.';
    }

    if (!data.mobile) {
      errors['pricing-mobile'] = 'Please enter your mobile number.';
    }

    if (!data.email) {
      errors['pricing-email'] = 'Please enter your email id.';
    }

    if (!data.company) {
      errors['pricing-company'] = 'Please enter your company name.';
    } else if (companyField && !companyField.checkValidity()) {
      errors['pricing-company'] = 'Company name should only contain letters and spaces.';
    }

    if (!data.payment_mode) {
      errors['pricing-payment-mode'] = 'Please select a payment mode.';
    }

    // Only ever show the first problem — fix it, click again, see the next one.
    const order = ['pricing-name', 'pricing-mobile', 'pricing-email', 'pricing-company', 'pricing-payment-mode'];
    const firstFieldId = order.find(fieldId => errors[fieldId]);
    if (!firstFieldId) return null;

    showFieldError(firstFieldId, errors[firstFieldId]);
    return { fieldId: firstFieldId, message: errors[firstFieldId] };
  }

  buttons.forEach(button => {
    button.addEventListener('click', async () => {
      const formData = collectFormData();
      const validationError = validate(formData);
      if (validationError) {
        document.getElementById(validationError.fieldId)?.focus();
        return;
      }

      const plan = button.dataset.payPlan;
      button.disabled = true;
      const originalText = button.textContent;
      button.textContent = 'Please wait…';

      try {
        const initiateResponse = await fetch(window.BX_PRICING.initiateUrl, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrfToken,
          },
          body: JSON.stringify({ ...formData, selected_plan: plan }),
        });
        const order = await initiateResponse.json();
        if (!initiateResponse.ok) {
          throw new Error(order.message || 'Could not start the payment.');
        }

        const checkout = new Razorpay({
          key: order.key,
          order_id: order.order_id,
          amount: order.amount,
          currency: order.currency,
          name: 'BusinessX',
          description: plan.charAt(0).toUpperCase() + plan.slice(1) + ' Membership',
          prefill: { name: order.name, email: order.email, contact: order.mobile },
          theme: { color: '#c7962f' },
          handler: async response => {
            try {
              const verifyResponse = await fetch(window.BX_PRICING.verifyUrl, {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  Accept: 'application/json',
                  'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                  order_no: order.order_no,
                  razorpay_order_id: response.razorpay_order_id,
                  razorpay_payment_id: response.razorpay_payment_id,
                  razorpay_signature: response.razorpay_signature,
                }),
              });
              const result = await verifyResponse.json();
              if (!verifyResponse.ok || !result.success) {
                throw new Error(result.message || 'Payment verification failed.');
              }
              showStatus('Payment successful! Redirecting to complete your profile…', 'success');
              window.location.href = result.redirect;
            } catch (error) {
              showStatus(error.message, 'error');
            }
          },
          modal: {
            ondismiss: () => {
              button.disabled = false;
              button.textContent = originalText;
            },
          },
        });
        checkout.open();
      } catch (error) {
        showStatus(error.message, 'error');
      } finally {
        button.disabled = false;
        button.textContent = originalText;
      }
    });
  });

  // Clear a field's inline error as soon as the visitor fixes it.
  ['pricing-name', 'pricing-mobile', 'pricing-email', 'pricing-company', 'pricing-payment-mode'].forEach(fieldId => {
    const field = document.getElementById(fieldId);
    field?.addEventListener('input', () => {
      field.classList.remove('has-error');
      const errorEl = document.querySelector(`.field-error[data-error-for="${fieldId}"]`);
      if (errorEl) {
        errorEl.textContent = '';
        errorEl.classList.remove('is-visible');
      }
    });
  });
});
