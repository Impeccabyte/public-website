/**
 * Accordion: buttons toggle labelled regions. One open at a time unless
 * the root has data-accordion-multiple. Panels open and close by height;
 * the motion is switched off under prefers-reduced-motion in a11y.css.
 */
((Drupal, once) => {
  Drupal.behaviors.impeccabyteAccordion = {
    attach(context) {
      once('ib-accordion', '[data-accordion]', context).forEach((root) => {
        const multiple = root.hasAttribute('data-accordion-multiple');
        const triggers = Array.from(root.querySelectorAll('.accordion__trigger'));
        const panelOf = (trigger) => root.querySelector(`#${trigger.getAttribute('aria-controls')}`);
        const set = (trigger, open) => {
          trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
          panelOf(trigger)?.classList.toggle('is-open', open);
        };
        // From here the is-open class shows and hides panels, so they can
        // animate; the hidden attribute was only the no-script fallback.
        triggers.forEach((trigger) => {
          const panel = panelOf(trigger);
          if (!panel) return;
          panel.classList.toggle('is-open', trigger.getAttribute('aria-expanded') === 'true');
          panel.hidden = false;
        });
        triggers.forEach((trigger) => {
          trigger.addEventListener('click', () => {
            const open = trigger.getAttribute('aria-expanded') !== 'true';
            if (open && !multiple) triggers.forEach((t) => set(t, false));
            set(trigger, open);
          });
        });
      });
    },
  };
})(Drupal, once);
