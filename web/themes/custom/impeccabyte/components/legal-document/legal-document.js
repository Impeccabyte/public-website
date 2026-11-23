/**
 * @file
 * Clears the loading state once GetTerms has put the policy in place, turns
 * the policy's h1 into an h2 under the page hero, and shows a fallback
 * message if the policy has not arrived after 15 seconds.
 */
(function (Drupal, once) {
  const WAIT = 15000;

  Drupal.behaviors.impeccabyteLegalDocument = {
    attach(context) {
      once('legal-document', '[data-legal-document]', context).forEach((frame) => {
        const body = frame.querySelector('.getterms-document-embed');
        if (!body) {
          return;
        }
        const loaded = () => !body.querySelector('.legal-document__skeleton');
        let timer = 0;
        const observer = new MutationObserver(() => {
          if (loaded()) {
            demoteTitle();
            finish();
          }
        });
        // The page hero is the page's h1, so the policy's own h1 becomes an
        // h2 styled as the document title.
        function demoteTitle() {
          body.querySelectorAll('h1').forEach((h1) => {
            const h2 = document.createElement('h2');
            h2.className = 'legal-document__title';
            if (h1.id) {
              h2.id = h1.id;
            }
            h2.append(...h1.childNodes);
            h1.replaceWith(h2);
          });
        }
        function finish() {
          observer.disconnect();
          window.clearTimeout(timer);
          frame.removeAttribute('aria-busy');
        }

        // The policy can arrive before this runs.
        if (loaded()) {
          demoteTitle();
          finish();
          return;
        }
        observer.observe(body, { childList: true });
        timer = window.setTimeout(() => {
          const skeleton = body.querySelector('.legal-document__skeleton');
          if (!skeleton) {
            demoteTitle();
            finish();
            return;
          }
          const label = (frame.dataset.legalLabel || '').toLowerCase();
          const email = frame.dataset.legalEmail || '';
          const message = document.createElement('p');
          message.className = 'legal-document__fallback';
          message.setAttribute('role', 'status');
          message.append(Drupal.t('This page is taking longer than usual to load. Refresh the page, or email') + ' ');
          if (email) {
            const link = document.createElement('a');
            link.href = 'mailto:' + email;
            link.textContent = email;
            message.append(link, ' ');
          }
          message.append(Drupal.t('for a copy of our @label.', { '@label': label }));
          skeleton.replaceWith(message);
          body.querySelector('.visually-hidden[role="status"]')?.remove();
          finish();
        }, WAIT);
      });
    },
  };
})(Drupal, once);
