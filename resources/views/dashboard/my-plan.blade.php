@extends('layouts.dashboard')

@section('title')
My Plan | BusinessX
@endsection
@section('description')
View your active BusinessX membership or explore available plans.
@endsection

@section('head')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/account-dashboard.css?v=20261014') }}">
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
  window.BX_MY_PLAN_PAYMENT = {
    initiateUrl: @json(route('dashboard.my-plan.payment.initiate')),
    verifyUrl: @json(route('dashboard.my-plan.payment.verify')),
  };
</script>
@endsection

@section('content')
<div class="dash-shell">
  @include('dashboard.partials.sidebar')

  <div class="dash-main">
    @include('dashboard.partials.header')

    <main class="account-dashboard">
      <section class="workspace-panel my-plan-panel" aria-labelledby="upgrade-plan-title">
        <h1 id="upgrade-plan-title" class="my-plan-page-heading">Upgrade Plan</h1>
        <h2 class="my-plan-membership-title">Membership Details</h2>
        <div class="interaction-table-wrap">
          <table class="interaction-table my-plan-table">
            <thead>
              <tr>
                <th scope="col">Profile - Plan</th>
                <th scope="col">Amount</th>
                <th scope="col">Payment Date</th>
                <th scope="col">Activation - Expiry Date</th>
                <th scope="col">Interaction - Insta Credits (Total/Used)</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($activeMemberships as $membership)
                <tr>
                  <td>{{ $profileTypeLabels[$membership->profile_type] ?? 'Profile' }} - {{ $membership->plan_name ?: ($membership->payment_comments ?: 'Membership plan') }}</td>
                  <td>{{ $membership->amount !== '' ? $membership->amount : '—' }}</td>
                  <td>{{ $membership->created_at ? \Illuminate\Support\Carbon::parse($membership->created_at)->format('d-m-y') : '—' }}</td>
                  <td>
                    {{ $membership->activation_date ? \Illuminate\Support\Carbon::parse($membership->activation_date)->format('d-m-y') : '—' }}
                    -
                    {{ $membership->expiry_date ? \Illuminate\Support\Carbon::parse($membership->expiry_date)->format('d-m-y') : 'No expiry' }}
                  </td>
                  <td>
                    {{ filled($membership->interaction_credits) ? $membership->interaction_credits : '0' }} / 0
                    <span class="my-plan-credit-separator"></span>
                    {{ filled($membership->instant_responses) ? $membership->instant_responses : '0' }} / 0
                  </td>
                </tr>
              @empty
                <tr><td colspan="5" class="my-plan-no-membership">No active membership found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if (count($availablePlans) > 0)
          <form class="my-plan-selection" data-my-plan-form>
            @if ($upgradableProfiles->isEmpty())
              <p class="my-plan-action-message" role="status">You don't have an active profile yet. <a href="{{ route('dashboard.profiles.create') }}">Create a profile</a> before upgrading to a paid plan.</p>
            @else
              <div class="my-plan-profile-row">
                <label for="my-plan-profile">Profile to upgrade <span>*</span></label>
                <div class="my-plan-profile-select-wrap">
                  <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/></svg>
                  <select id="my-plan-profile" data-my-plan-profile required>
                    @foreach ($upgradableProfiles as $profile)
                      <option value="{{ $profile->type }}:{{ $profile->id }}">{{ $profile->label }}</option>
                    @endforeach
                  </select>
                  <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
              </div>
            @endif

            <fieldset class="my-plan-grid" aria-label="Available membership plans">
              @foreach ($availablePlans as $plan)
                <label class="my-plan-card my-plan-card-{{ $plan['key'] }}">
                  <span class="my-plan-choice">
                    <input type="radio" name="selected_plan" value="{{ $plan['key'] }}" @checked($loop->first) required>
                    <span class="my-plan-name">{{ $plan['name'] }}</span>
                    @if ($plan['key'] === 'gold')
                      <span class="my-plan-recommend">Recommend</span>
                    @endif
                  </span>
                  <span class="my-plan-price">{{ $plan['price'] }}<small>{{ $plan['period'] === 'Month' ? ' / month' : '' }}</small></span>
                  <span class="my-plan-duration">{{ $plan['period'] }}</span>
                  <span class="my-plan-benefits-title">{{ $plan['description'] }}</span>
                  <span class="my-plan-benefits">
                    @foreach ($plan['benefits'] as $benefit)
                      <span>{{ $benefit }}</span>
                    @endforeach
                  </span>
                </label>
              @endforeach
            </fieldset>

            <div class="my-plan-promo-row">
              <label for="my-plan-promo">Promo Code:</label>
              <div class="my-plan-promo-control">
                <input id="my-plan-promo" type="text" placeholder="Enter Promo Code" autocomplete="off">
                <button type="button" data-my-plan-promo-apply>Apply</button>
              </div>
            </div>

            <fieldset class="my-plan-payment">
              <legend>Payment Mode <span>*</span>:</legend>
              <label><input type="radio" name="payment_mode" value="online" checked required> Online</label>
              <label><input type="radio" name="payment_mode" value="upi"> UPI</label>
            </fieldset>
            <p class="my-plan-action-message" data-my-plan-message role="status" hidden></p>
            <button class="my-plan-submit" type="submit">Submit</button>
          </form>
        @else
          <div class="account-empty-state my-plan-empty">
            <h3>No plan offers are available right now</h3>
            <p>Please check back later for available membership plans.</p>
          </div>
        @endif
      </section>
    </main>
  </div>
</div>
@if (count($availablePlans) > 0)
  <script>
    (() => {
      const form = document.querySelector('[data-my-plan-form]');
      if (!form) return;

      const message = form.querySelector('[data-my-plan-message]');
      const submitButton = form.querySelector('.my-plan-submit');
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

      const announce = (text) => {
        message.textContent = text;
        message.hidden = false;
      };

      form.querySelector('[data-my-plan-promo-apply]')?.addEventListener('click', () => {
        announce('Promo code validation is not available yet.');
      });

      form.addEventListener('submit', async (event) => {
        event.preventDefault();
        if (!form.reportValidity()) return;

        const selectedPlan = form.querySelector('input[name="selected_plan"]:checked')?.value;
        const paymentMode = form.querySelector('input[name="payment_mode"]:checked')?.value;
        const profileField = form.querySelector('[data-my-plan-profile]');

        if (selectedPlan === 'free') {
          announce("You're already set up on the Free plan — nothing to pay.");
          return;
        }
        if (selectedPlan === 'platinum') {
          announce('Platinum is a custom plan — please contact us to upgrade.');
          return;
        }
        if (!profileField || !profileField.value) {
          announce('Please create a profile before upgrading to a paid plan.');
          return;
        }
        if (typeof Razorpay === 'undefined') {
          announce('Online payment is not available right now. Please try again later.');
          return;
        }

        const [profileType, profileId] = profileField.value.split(':');
        const originalText = submitButton.textContent;
        submitButton.disabled = true;
        submitButton.textContent = 'Please wait…';

        try {
          const initiateResponse = await fetch(window.BX_MY_PLAN_PAYMENT.initiateUrl, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              Accept: 'application/json',
              'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
              selected_plan: selectedPlan,
              billing_period: 'monthly',
              profile_type: profileType,
              profile_id: profileId,
              payment_mode: paymentMode,
            }),
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
            description: selectedPlan.charAt(0).toUpperCase() + selectedPlan.slice(1) + ' Membership Upgrade',
            prefill: { name: order.name, email: order.email, contact: order.mobile },
            theme: { color: '#c7962f' },
            handler: async response => {
              try {
                const verifyResponse = await fetch(window.BX_MY_PLAN_PAYMENT.verifyUrl, {
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
                announce('Payment successful! Your plan is being upgraded…');
                window.location.href = result.redirect;
              } catch (error) {
                announce(error.message);
              }
            },
            modal: {
              ondismiss: () => {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
              },
            },
          });
          checkout.open();
        } catch (error) {
          announce(error.message);
        } finally {
          submitButton.disabled = false;
          submitButton.textContent = originalText;
        }
      });
    })();
  </script>
@endif
@endsection
