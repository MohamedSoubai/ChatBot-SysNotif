<style>
#sg-chatbot { position: fixed; bottom: 20px; right: 20px; z-index: 1050; }
#sg-chatbot .chat-card { width: 320px; max-height: 480px; }
#sg-chatbot .chat-body { height: 300px; overflow-y: auto; }
#sg-chatbot .msg { padding: 8px 10px; border-radius: 8px; margin-bottom: 8px; font-size: 0.92rem; }
#sg-chatbot .msg.me { background: #e9f2ff; align-self: flex-end; }
#sg-chatbot .msg.bot { background: #f1f3f5; }
#sg-chatbot .bubble { display: inline-block; max-width: 100%; word-wrap: break-word; }
#sg-chatbot .chat-input { display: flex; gap: 8px; }
.sg-hidden { display: none !important; }
</style>

<div id="sg-chatbot" class="d-none d-md-block">
  <div class="card shadow chat-card">
    <div class="card-header py-2 d-flex align-items-center justify-content-between">
      <span><i class="fas fa-robot text-primary"></i> Assistant Factures</span>
      <button id="sg-chatbot-close" class="btn btn-sm btn-light"><i class="fas fa-times"></i></button>
    </div>
    <div class="card-body chat-body d-flex flex-column" id="sg-chatbot-messages"></div>
    <div class="card-footer p-2">
      <form id="sg-chatbot-form" class="chat-input" onsubmit="return false;" novalidate>
        <input type="text" id="sg-chatbot-input" class="form-control form-control-sm" placeholder="Posez une question (ex: Statut facture #F123)…" autocomplete="off" />
        <button type="submit" class="btn btn-primary btn-sm">
          <i class="fas fa-paper-plane"></i>
        </button>
      </form>
    </div>
  </div>
</div>

<button id="sg-chatbot-toggle" class="btn btn-primary rounded-circle shadow" style="position: fixed; bottom: 20px; right: 20px; width: 52px; height: 52px; z-index: 1040;">
  <i class="fas fa-comments"></i>
</button>

<script>
(function() {
  function initChatbot() {
    var $ = window.jQuery;
    if (!$) { setTimeout(initChatbot, 50); return; }

    const $toggle = $('#sg-chatbot-toggle');
    const $panel = $('#sg-chatbot');
    const $close = $('#sg-chatbot-close');
    const $form = $('#sg-chatbot-form');
    const $input = $('#sg-chatbot-input');
    const $msgs = $('#sg-chatbot-messages');
    const token = $('meta[name="csrf-token"]').attr('content');

    function appendMessage(text, who) {
      const cls = who === 'me' ? 'me text-dark' : 'bot text-dark';
      $msgs.append('<div class="msg ' + cls + '"><span class="bubble">' + $('<div>').text(text).html() + '</span></div>');
      $msgs.scrollTop($msgs[0].scrollHeight);
    }

    function setBusy(b) { $input.prop('disabled', b); $form.find('button[type=submit]').prop('disabled', b); }

    $toggle.on('click', function() { $panel.toggleClass('sg-hidden'); if ($panel.is(':visible') && !$panel.hasClass('sg-hidden')) { $input.focus(); }});
    $close.on('click', function() { $panel.addClass('sg-hidden'); });

    $form.on('submit', function(e) {
      e.preventDefault();
      const message = ($input.val() || '').trim();
      if (!message) return;

      appendMessage(message, 'me');
      $input.val('');
      setBusy(true);

      $.ajax({
        url: '{{ route('chat.query') }}',
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token },
        data: { message },
      }).done(function(data) {
        const reply = (data && data.response) ? String(data.response) : 'Réponse indisponible.';
        appendMessage(reply, 'bot');
      }).fail(function(xhr) {
        const msg = xhr && xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Erreur réseau.';
        appendMessage('Échec de la requête: ' + msg, 'bot');
      }).always(function() {
        setBusy(false);
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initChatbot);
  } else {
    initChatbot();
  }
})();
</script> 