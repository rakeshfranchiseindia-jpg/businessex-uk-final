@extends('layouts.dashboard')

@section('title')
My Plan | BusinessX
@endsection
@section('description')
View your active BusinessX membership or explore available plans.
@endsection

@section('head')
<link rel="stylesheet" href="{{ asset('css/account-dashboard.css') }}">
@endsection

@section('content')
<div class="workspace-shell">
  <aside class="workspace-sidebar">
    <a class="workspace-brand" href="{{ route('home') }}" aria-label="BusinessX home"><img src="{{ asset('assets/img/businessx-logo.png?v=20261008') }}" alt="BusinessX"></a>
    <div class="workspace-user"><strong>{{ auth()->user()->name }}</strong><span>{{ \Illuminate\Support\Str::title(auth()->user()->reg_profile ?: 'member') }} account</span></div>
    <nav aria-label="Account navigation">
      <a href="{{ route('dashboard.index') }}">Dashboard</a>
      <a href="{{ route('dashboard.profile') }}">Edit Profile</a>
      <a href="{{ route('dashboard.password') }}">Change Password</a>
      <a href="{{ route('dashboard.my-plan') }}" aria-current="page">My Plan</a>
      <span class="workspace-nav-heading">My Interaction</span>
      <a href="{{ route('dashboard.inbox') }}">BX Inbox</a>
      <a href="{{ route('dashboard.proposals.sent') }}">Proposal sent</a>
      <a href="{{ route('dashboard.proposals.received') }}">Proposal received</a>
      <a href="{{ route('dashboard.instant-response') }}">Instant Response</a>
      <a href="#dashboard-live-chat" data-open-live-chat>Live Chat</a>
    </nav>
  </aside>

  <div class="workspace-main">
    <header class="workspace-topbar">
      <strong>My Account / My Plan</strong>
      <form method="post" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="workspace-signout">Sign Out</button>
      </form>
    </header>
    <main class="workspace-content">
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
                  <td>{{ $profileTypeLabels[$membership->profile_type] ?? 'Profile' }} - {{ $membership->plan_name ?: 'Membership plan' }}</td>
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
              <label><input type="radio" name="payment_mode" value="credit_card" required> Credit Card</label>
              <label><input type="radio" name="payment_mode" value="debit_card"> Debit Card</label>
              <label><input type="radio" name="payment_mode" value="net_banking"> Net Banking</label>
              <label><input type="radio" name="payment_mode" value="paytm"> Paytm</label>
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
      const announce = (text) => {
        message.textContent = text;
        message.hidden = false;
      };

      form.addEventListener('submit', (event) => {
        event.preventDefault();
        if (!form.reportValidity()) return;
        announce('Checkout is not available yet. Your plan has not been changed and no payment was taken.');
      });

      form.querySelector('[data-my-plan-promo-apply]').addEventListener('click', () => {
        announce('Promo code validation is not available yet.');
      });
    })();
  </script>
@endif
@endsection
