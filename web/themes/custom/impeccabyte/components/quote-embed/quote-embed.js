/**
 * @file
 * Shows the app's quote form once its iframe has loaded, and a fallback
 * message if it has not after 15 seconds. A form that arrives later still
 * replaces the fallback.
 */
(function (Drupal, once) {
  const WAIT = 15000;

  Drupal.behaviors.impeccabyteQuoteEmbed = {
    attach(context) {
      once('quote-embed', '[data-quote-embed]', context).forEach((wrapper) => {
        const frame = wrapper.querySelector('[data-ib-form]');
        if (!frame) {
          return;
        }
        let timer = 0;
        const ready = () => {
          window.clearTimeout(timer);
          // A form that arrives after the fallback replaces it.
          frame.querySelector('.quote-embed__fallback')?.remove();
          wrapper.classList.add('is-loaded');
          wrapper.removeAttribute('aria-busy');
        };
        // The app's script sizes the iframe (640px to start) once the form
        // inside reports its height, so any other height means the form has
        // loaded. That also covers a form that loaded before this ran, when
        // its load event has already fired.
        const resized = (iframe) => iframe.style.height && iframe.style.height !== '640px';
        const watch = (iframe) => {
          if (iframe.dataset.quoteWatched) {
            return;
          }
          iframe.dataset.quoteWatched = '1';
          if (resized(iframe)) {
            ready();
            return;
          }
          iframe.addEventListener('load', ready, { once: true });
          const sizes = new MutationObserver(() => {
            if (resized(iframe)) {
              sizes.disconnect();
              ready();
            }
          });
          sizes.observe(iframe, { attributes: true, attributeFilter: ['style'] });
        };
        const existing = frame.querySelector('iframe');
        if (existing) {
          watch(existing);
        }
        const observer = new MutationObserver(() => {
          const iframe = frame.querySelector('iframe');
          if (iframe) {
            observer.disconnect();
            watch(iframe);
          }
        });
        observer.observe(frame, { childList: true });

        timer = window.setTimeout(() => {
          if (wrapper.classList.contains('is-loaded')) {
            return;
          }
          const email = wrapper.dataset.quoteEmail || '';
          const message = document.createElement('p');
          message.className = 'quote-embed__fallback';
          message.setAttribute('role', 'status');
          message.append(Drupal.t('The quote form is taking longer than usual to load. Refresh the page, or email') + ' ');
          if (email) {
            const link = document.createElement('a');
            link.href = 'mailto:' + email;
            link.textContent = email;
            message.append(link, ' ');
          }
          message.append(Drupal.t('and a local specialist will reply.'));
          frame.querySelector('.quote-embed__skeleton')?.replaceWith(message);
          frame.querySelector('.visually-hidden[role="status"]')?.remove();
          wrapper.removeAttribute('aria-busy');
        }, WAIT);
      });
    },
  };
})(Drupal, once);
