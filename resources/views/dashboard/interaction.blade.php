@extends('layouts.dashboard')

<?php
$interactionPages = [
    'inbox' => [
        'title' => 'BX Inbox',
        'description' => 'View and manage conversations with businesses in your network.',
        'emptyTitle' => 'Your inbox is clear',
        'emptyDescription' => 'New messages from your business connections will appear here.',
    ],
    'sent' => [
        'title' => 'Proposal sent',
        'description' => 'Keep track of proposals you have sent to other businesses.',
        'emptyTitle' => 'No proposals sent yet',
        'emptyDescription' => 'Proposals you send to businesses will be listed here with their current status.',
    ],
    'received' => [
        'title' => 'Proposal received',
        'description' => 'Review and respond to proposals received from other businesses.',
        'emptyTitle' => 'No proposals received',
        'emptyDescription' => 'When a business sends you a proposal, you can review it here.',
    ],
    'instant-response' => [
        'title' => 'Instant Response',
        'description' => 'Set an automatic reply for new messages when you are unavailable.',
        'emptyTitle' => '',
        'emptyDescription' => '',
    ],
];

$interactionPage = $interactionPages[$interactionPageKey] ?? $interactionPages['inbox'];
$interactionLinks = [
    'inbox' => ['label' => 'BX Inbox', 'route' => 'dashboard.inbox'],
    'sent' => ['label' => 'Proposal sent', 'route' => 'dashboard.proposals.sent'],
    'received' => ['label' => 'Proposal received', 'route' => 'dashboard.proposals.received'],
    'instant-response' => ['label' => 'Instant Response', 'route' => 'dashboard.instant-response'],
];
?>

@section('title')
<?= htmlspecialchars($interactionPage['title'], ENT_QUOTES, 'UTF-8') ?> | BusinessX
@endsection
@section('description')
Manage your BusinessX interactions.
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
        <a href="{{ route('dashboard.my-plan') }}">My Plan</a>
        <span class="workspace-nav-heading">My Interaction</span>
        <?php foreach ($interactionLinks as $key => $link): ?>
          <a href="{{ route($link['route']) }}"<?= $key === $interactionPageKey ? ' aria-current="page"' : '' ?>>{{ $link['label'] }}</a>
        <?php endforeach; ?>
        <a href="#dashboard-live-chat" data-open-live-chat>Live Chat</a>
      </nav>
    </aside>

    <div class="workspace-main">
      <header class="workspace-topbar">
        <strong>My Account / My Interaction / <?= htmlspecialchars($interactionPage['title'], ENT_QUOTES, 'UTF-8') ?></strong>
        <form method="post" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="workspace-signout">Sign Out</button>
        </form>
      </header>
      <main class="workspace-content">
        <div class="page-title-row">
          <div>
            <h1><?= htmlspecialchars($interactionPage['title'], ENT_QUOTES, 'UTF-8') ?></h1>
            <p><?= htmlspecialchars($interactionPage['description'], ENT_QUOTES, 'UTF-8') ?></p>
          </div>
          <a class="account-primary-action" href="{{ route('dashboard.index') }}">Back to Dashboard</a>
        </div>

        <?php if ($interactionPageKey === 'instant-response'): ?>
          <section class="workspace-panel" aria-labelledby="instant-response-title">
            <h2 id="instant-response-title">Automatic reply</h2>
            <form id="instant-response-form" method="post" action="{{ route('dashboard.instant-response.save') }}">
              @csrf
              <label class="interaction-toggle-row" for="instant-response-enabled">
                <span><strong>Enable instant response</strong><span>Automatically reply to new messages.</span></span>
                <input id="instant-response-enabled" name="enabled" type="checkbox" value="1" @checked($instantResponseEnabled)>
              </label>
              <div class="workspace-field" style="margin-top:18px;">
                <label for="instant-response-message">Response message</label>
                <textarea class="interaction-textarea" id="instant-response-message" name="message" rows="5" maxlength="500" required>{{ old('message', $instantResponseMessage) }}</textarea>
                @error('message')<p class="inbox-validation-error" role="alert">{{ $message }}</p>@enderror
              </div>
              <button class="workspace-submit" type="submit">Save response</button>
              @if (session('status'))<p class="workspace-status interaction-status" role="status" aria-live="polite">{{ session('status') }}</p>@endif
            </form>
          </section>
        <?php elseif ($interactionPageKey === 'sent' || $interactionPageKey === 'received'): ?>
          @if ($interactionPageKey === 'sent')
            <section class="workspace-panel" aria-labelledby="proposal-compose-title">
              <h2 id="proposal-compose-title">Send a proposal</h2>
              <p>Select one of your active profiles and a member who has contacted you about that profile.</p>
              @if ($proposalProfiles->isEmpty())
                <p>You need an active profile before you can send a proposal.</p>
              @else
                <form class="inbox-reply-form proposal-compose-form" method="post" action="{{ route('dashboard.proposals.store') }}" enctype="multipart/form-data">
                  @csrf
                  <label for="proposal-profile-choice">Your profile</label>
                  <select id="proposal-profile-choice" name="profile" required>
                    <option value="">Select a profile</option>
                    @foreach ($proposalProfiles as $profile)
                      <option value="{{ $profile->value }}" @selected(old('profile') === $profile->value)>{{ $profile->label }}</option>
                    @endforeach
                  </select>
                  @error('profile')<p class="inbox-validation-error" role="alert">{{ $message }}</p>@enderror
                  <label for="proposal-recipient-choice">Interested member</label>
                  <select id="proposal-recipient-choice" name="recipient_user_id" required @disabled($proposalRecipients->isEmpty())>
                    <option value="">{{ $proposalRecipients->isEmpty() ? 'No interested members yet' : 'Select a member who contacted this profile' }}</option>
                    @foreach ($proposalRecipients as $recipient)
                      <option value="{{ $recipient->user_id }}" data-profile="{{ $recipient->profile }}" @selected((string) old('recipient_user_id') === (string) $recipient->user_id)>{{ $recipient->label }}</option>
                    @endforeach
                  </select>
                  @error('recipient_user_id')<p class="inbox-validation-error" role="alert">{{ $message }}</p>@enderror
                  @if ($proposalRecipients->isEmpty())
                    <p class="proposal-recipient-empty">No interested members yet. Members will appear here after they contact one of your profiles.</p>
                  @endif
                  <label for="selected-proposal-title">Proposal title</label>
                  <input id="selected-proposal-title" name="title" type="text" maxlength="255" value="{{ old('title') }}" required>
                  @error('title')<p class="inbox-validation-error" role="alert">{{ $message }}</p>@enderror
                  <label for="selected-proposal-amount">Amount (£, optional)</label>
                  <input id="selected-proposal-amount" name="amount" type="number" min="0" step="0.01" value="{{ old('amount') }}">
                  @error('amount')<p class="inbox-validation-error" role="alert">{{ $message }}</p>@enderror
                  <label for="selected-proposal-description">Proposal details</label>
                  <textarea id="selected-proposal-description" name="description" rows="5" maxlength="10000" required>{{ old('description') }}</textarea>
                  @error('description')<p class="inbox-validation-error" role="alert">{{ $message }}</p>@enderror
                  <label for="selected-proposal-attachments">Attachments (up to 5 files, 10 MB each)</label>
                  <input id="selected-proposal-attachments" name="attachments[]" type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.png,.jpg,.jpeg" multiple>
                  @error('attachments')<p class="inbox-validation-error" role="alert">{{ $message }}</p>@enderror
                  @error('attachments.*')<p class="inbox-validation-error" role="alert">{{ $message }}</p>@enderror
                  <button class="workspace-submit" type="submit" @disabled($proposalRecipients->isEmpty())>Send Proposal</button>
                </form>
              @endif
            </section>
            <script>
              const proposalProfileChoice = document.getElementById('proposal-profile-choice');
              const proposalRecipientChoice = document.getElementById('proposal-recipient-choice');
              if (proposalProfileChoice && proposalRecipientChoice) {
                const updateProposalRecipients = () => {
                  const profile = proposalProfileChoice.value;
                  let hasEligibleRecipient = false;
                  const hasRecipients = proposalRecipientChoice.options.length > 1;
                  for (const option of proposalRecipientChoice.options) {
                    if (!option.value) continue;
                    option.hidden = option.dataset.profile !== profile;
                    option.disabled = option.hidden;
                    if (!option.hidden) hasEligibleRecipient = true;
                  }
                  proposalRecipientChoice.disabled = !hasRecipients;
                  if (!proposalRecipientChoice.selectedOptions[0]
                    || proposalRecipientChoice.selectedOptions[0].disabled) {
                    proposalRecipientChoice.value = '';
                  }
                  proposalRecipientChoice.options[0].textContent = !hasRecipients
                    ? 'No interested members yet'
                    : (profile && !hasEligibleRecipient
                      ? 'No interested members for this profile'
                      : 'Select a member who contacted this profile');
                };
                proposalProfileChoice.addEventListener('change', updateProposalRecipients);
                updateProposalRecipients();
              }
            </script>
          @endif
          <section class="workspace-panel" aria-labelledby="proposal-list-title">
            <div class="page-title-row proposal-list-heading">
              <h2 id="proposal-list-title">Proposal activity</h2>
              <form method="get" action="{{ $interactionPageKey === 'sent' ? route('dashboard.proposals.sent') : route('dashboard.proposals.received') }}" class="proposal-status-filter">
                <label for="proposal-status-filter">Status</label>
                <select id="proposal-status-filter" name="status" onchange="this.form.submit()">
                  <option value="all" @selected($selectedProposalStatus === 'all')>All statuses</option>
                  @foreach (['pending' => 'Pending', 'accepted' => 'Accepted', 'declined' => 'Declined'] as $statusKey => $statusLabel)
                    <option value="{{ $statusKey }}" @selected($selectedProposalStatus === $statusKey)>{{ $statusLabel }}</option>
                  @endforeach
                </select>
              </form>
            </div>
            <div class="interaction-table-wrap">
              <table class="interaction-table">
                <thead>
                  <tr>
                    <th>Profile</th>
                    <th><?= $interactionPageKey === 'sent' ? 'Sent to' : 'Received from' ?></th>
                    <th>Proposal</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($proposals as $proposal)
                    <tr>
                      <td>
                        <a href="{{ route('profile-details', ['type' => $proposal->profile_type, 'id' => $proposal->profile_id]) }}">
                          {{ $proposal->profile_title }}
                        </a>
                        <small class="proposal-profile-type">{{ \Illuminate\Support\Str::title($proposal->profile_type) }}</small>
                      </td>
                      <td>{{ $proposal->counterpart }}</td>
                      <td>
                        <a class="proposal-title-link" href="{{ route('dashboard.inbox.conversation', $proposal->conversation_id) }}">{{ $proposal->title }}</a>
                        <span class="proposal-description">{{ \Illuminate\Support\Str::limit($proposal->description, 140) }}</span>
                        @foreach ($proposal->attachments as $attachment)
                          <a class="proposal-attachment-link" href="{{ route('dashboard.proposals.attachments.download', [$proposal->id, $attachment->id]) }}">{{ $attachment->original_name }}</a>
                        @endforeach
                      </td>
                      <td>{{ $proposal->amount !== null ? '£' . number_format((float) $proposal->amount, 2) : '—' }}</td>
                      <td><time datetime="{{ $proposal->created_at }}">{{ \Illuminate\Support\Carbon::parse($proposal->created_at)->format('d M Y') }}</time></td>
                      <td><span class="proposal-status-badge is-{{ $proposal->status }}">{{ \Illuminate\Support\Str::title($proposal->status) }}</span></td>
                    </tr>
                  @empty
                    <tr><td colspan="6"><?= htmlspecialchars($interactionPage['emptyTitle'], ENT_QUOTES, 'UTF-8') ?>. <?= htmlspecialchars($interactionPage['emptyDescription'], ENT_QUOTES, 'UTF-8') ?></td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
            @if ($proposals->hasPages())
              <div class="proposal-pagination">{{ $proposals->links() }}</div>
            @endif
          </section>
        <?php else: ?>
          <?php if (isset($conversation)): ?>
            <section class="workspace-panel" aria-labelledby="conversation-title">
              <div class="page-title-row inbox-thread-heading">
                <div>
                  <h2 id="conversation-title"><?= htmlspecialchars($conversation->counterpart, ENT_QUOTES, 'UTF-8') ?></h2>
                  <p><?= htmlspecialchars($conversation->profile_label, ENT_QUOTES, 'UTF-8') ?></p>
                </div>
                <a class="account-primary-action" href="{{ route('dashboard.inbox') }}">Back to Inbox</a>
              </div>
              <?php if ($conversation->is_owner): ?>
                <p class="inbox-contact-details">
                  Contact details: <?= htmlspecialchars($conversation->sender_email, ENT_QUOTES, 'UTF-8') ?>
                  <?php if ($conversation->sender_phone): ?> · <?= htmlspecialchars($conversation->sender_phone, ENT_QUOTES, 'UTF-8') ?><?php endif; ?>
                </p>
              <?php endif; ?>
              @if ($proposals->isNotEmpty())
                <section class="conversation-proposals" aria-labelledby="conversation-proposals-title">
                  <h3 id="conversation-proposals-title">Proposals for this profile</h3>
                  @foreach ($proposals as $proposal)
                    <article class="conversation-proposal">
                      <div class="conversation-proposal-heading">
                        <strong>{{ $proposal->title }}</strong>
                        <span class="proposal-status-badge is-{{ $proposal->status }}">{{ \Illuminate\Support\Str::title($proposal->status) }}</span>
                      </div>
                      <p>{{ $proposal->description }}</p>
                      @if ($proposal->attachments->isNotEmpty())
                        <ul class="proposal-attachments" aria-label="Proposal attachments">
                          @foreach ($proposal->attachments as $attachment)
                            <li><a href="{{ route('dashboard.proposals.attachments.download', [$proposal->id, $attachment->id]) }}">{{ $attachment->original_name }}</a></li>
                          @endforeach
                        </ul>
                      @endif
                      <div class="conversation-proposal-meta">
                        @if ($proposal->amount !== null)<span>Amount: £{{ number_format((float) $proposal->amount, 2) }}</span>@endif
                        <span>Sent {{ \Illuminate\Support\Carbon::parse($proposal->created_at)->format('d M Y') }}</span>
                      </div>
                      @if (!$conversation->is_owner && $proposal->status === 'pending')
                        <form class="proposal-response-actions" method="post" action="{{ route('dashboard.proposals.status', $proposal->id) }}">
                          @csrf
                          @method('put')
                          <button type="submit" name="status" value="accepted">Accept proposal</button>
                          <button type="submit" name="status" value="declined">Decline proposal</button>
                        </form>
                      @elseif ($proposal->responded_at)
                        <p class="proposal-response-date">Responded {{ \Illuminate\Support\Carbon::parse($proposal->responded_at)->format('d M Y') }}</p>
                      @endif
                    </article>
                  @endforeach
                </section>
              @endif
              <p class="inbox-live-status" data-live-status role="status" aria-live="polite">Connecting to live chat…</p>
              <div class="inbox-message-list" aria-label="Conversation messages" data-live-conversation="{{ $conversation->id }}" data-current-user-id="{{ auth()->user()->user_id }}">
                <?php foreach ($messages as $message): ?>
                  <?php $isCurrentUser = (int) $message->sender_user_id === (int) auth()->user()->user_id; ?>
                  <article class="inbox-message<?= $isCurrentUser ? ' is-own-message' : '' ?>" data-message-id="{{ $message->id }}" data-sender-user-id="{{ $message->sender_user_id }}">
                    <div class="inbox-message-meta">
                      <strong><?= $isCurrentUser ? 'You' : htmlspecialchars($conversation->counterpart, ENT_QUOTES, 'UTF-8') ?></strong>
                      <time datetime="<?= htmlspecialchars($message->created_at, ENT_QUOTES, 'UTF-8') ?>">{{ \Illuminate\Support\Carbon::parse($message->created_at)->format('d M Y, H:i') }}</time>
                    </div>
                    <p><?= nl2br(e($message->message)) ?></p>
                  </article>
                <?php endforeach; ?>
              </div>
              <form class="inbox-reply-form" method="post" action="{{ route('dashboard.inbox.reply', $conversation->id) }}" data-live-message-form>
                @csrf
                <label for="inbox-reply-message">Write a reply</label>
                <textarea id="inbox-reply-message" name="message" rows="4" maxlength="10000" required>{{ old('message') }}</textarea>
                @error('message')<p class="inbox-validation-error" role="alert">{{ $message }}</p>@enderror
                <button class="workspace-submit" type="submit">Send Reply</button>
              </form>
              @if ($conversation->is_owner)
                <details class="send-proposal-panel">
                  <summary>Send a proposal to this interested member</summary>
                  <form class="inbox-reply-form" method="post" action="{{ route('dashboard.inbox.proposals.store', $conversation->id) }}" enctype="multipart/form-data">
                    @csrf
                    <label for="proposal-title">Proposal title</label>
                    <input id="proposal-title" name="title" type="text" maxlength="255" value="{{ old('title') }}" required>
                    @error('title')<p class="inbox-validation-error" role="alert">{{ $message }}</p>@enderror
                    <label for="proposal-amount">Amount (£, optional)</label>
                    <input id="proposal-amount" name="amount" type="number" min="0" step="0.01" value="{{ old('amount') }}">
                    @error('amount')<p class="inbox-validation-error" role="alert">{{ $message }}</p>@enderror
                    <label for="proposal-description">Proposal details</label>
                    <textarea id="proposal-description" name="description" rows="5" maxlength="10000" required>{{ old('description') }}</textarea>
                    @error('description')<p class="inbox-validation-error" role="alert">{{ $message }}</p>@enderror
                    <label for="conversation-proposal-attachments">Attachments (up to 5 files, 10 MB each)</label>
                    <input id="conversation-proposal-attachments" name="attachments[]" type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.png,.jpg,.jpeg" multiple>
                    @error('attachments')<p class="inbox-validation-error" role="alert">{{ $message }}</p>@enderror
                    @error('attachments.*')<p class="inbox-validation-error" role="alert">{{ $message }}</p>@enderror
                    <button class="workspace-submit" type="submit">Send Proposal</button>
                  </form>
                </details>
              @endif
            </section>
          <?php elseif ($conversations->isEmpty()): ?>
            <section class="workspace-panel interaction-empty" aria-labelledby="inbox-empty-title">
              <span class="empty-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="m3 7 9 6 9-6"/></svg></span>
              <h2 id="inbox-empty-title"><?= htmlspecialchars($interactionPage['emptyTitle'], ENT_QUOTES, 'UTF-8') ?></h2>
              <p><?= htmlspecialchars($interactionPage['emptyDescription'], ENT_QUOTES, 'UTF-8') ?></p>
            </section>
          <?php else: ?>
            <section class="workspace-panel" aria-labelledby="inbox-title">
              <h2 id="inbox-title">Messages</h2>
              <div class="inbox-conversation-list">
                <?php foreach ($conversations as $item): ?>
                  <a class="inbox-conversation-row" href="{{ route('dashboard.inbox.conversation', $item->id) }}">
                    <span class="inbox-conversation-copy">
                      <strong><?= htmlspecialchars($item->counterpart, ENT_QUOTES, 'UTF-8') ?></strong>
                      <span><?= htmlspecialchars($item->profile_label, ENT_QUOTES, 'UTF-8') ?></span>
                      <span class="inbox-message-preview"><?= htmlspecialchars(\Illuminate\Support\Str::limit($item->last_message ?? '', 150), ENT_QUOTES, 'UTF-8') ?></span>
                    </span>
                    <time datetime="<?= htmlspecialchars($item->updated_at, ENT_QUOTES, 'UTF-8') ?>">{{ \Illuminate\Support\Carbon::parse($item->updated_at)->format('d M Y') }}</time>
                  </a>
                <?php endforeach; ?>
              </div>
            </section>
          <?php endif; ?>
        <?php endif; ?>
      </main>
    </div>
  </div>

  

  <?php if ($interactionPageKey === 'instant-response'): ?>
  
  <?php endif; ?>

@endsection
