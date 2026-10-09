<section class="bx-chatbot" data-chatbot data-message-url="{{ route('chatbot.respond') }}" data-lead-url="{{ route('chatbot.leads.store') }}" aria-label="BusinessX visitor assistant">
  <button class="bx-chatbot-launcher" type="button" data-chatbot-open aria-expanded="false" aria-controls="bx-chatbot-panel">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 11.5a7.5 7.5 0 0 1-7.5 7.5H5l1.4-3.1A7.5 7.5 0 1 1 20 11.5Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.5 11.5h.01m3.49 0h.01m3.49 0h.01" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>
    <span>Chat with us</span>
  </button>
  <section class="bx-chatbot-panel" id="bx-chatbot-panel" data-chatbot-panel hidden aria-labelledby="bx-chatbot-title">
    <header class="bx-chatbot-header">
      <span class="bx-chatbot-avatar" aria-hidden="true">BX</span>
      <span class="bx-chatbot-heading"><strong id="bx-chatbot-title">BusinessX Assistant</strong><small>Here to help you find your way</small></span>
      <button class="bx-chatbot-close" type="button" data-chatbot-close aria-label="Close chat">&times;</button>
    </header>
    <div class="bx-chatbot-messages" data-chatbot-messages role="log" aria-live="polite" aria-relevant="additions text">
      <p class="bx-chatbot-bubble is-bot">Hi! Ask me about business listings, investors, startups, mentors, or getting started.</p>
    </div>
    <div class="bx-chatbot-fallback" data-chatbot-fallback hidden>
      <form data-chatbot-lead-form>
        <p>Leave your details and our team can follow up:</p>
        <label>Name<input name="name" autocomplete="name" maxlength="255" required></label>
        <label>Email<input name="email" type="email" autocomplete="email" maxlength="255" required></label>
        <label>Mobile<div class="phone-input-group">
          @include('components.phone-country-code')
          <input name="mobile" type="tel" autocomplete="tel" maxlength="32" required>
        </div></label>
        <label>Your question<textarea name="message" data-chatbot-lead-message rows="3" maxlength="2000" required></textarea></label>
        <button type="submit" data-chatbot-lead-submit>Send details</button>
        <p class="bx-chatbot-privacy">Your details will only be used to respond to this enquiry. See our <a href="{{ route('privacy-policy') }}">Privacy Policy</a>.</p>
      </form>
    </div>
    <form class="bx-chatbot-compose" data-chatbot-message-form>
      @csrf
      {{--<label class="sr-only" for="bx-chatbot-question">Your question</label>--}}
      <input id="bx-chatbot-question" name="message" placeholder="Type your question..." maxlength="500" autocomplete="off" required>
      <button type="submit" aria-label="Send message" data-chatbot-send>
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 4 16 8-16 8 3-8-3-8Zm3 8h13" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </button>
    </form>
  </section>
</section>
