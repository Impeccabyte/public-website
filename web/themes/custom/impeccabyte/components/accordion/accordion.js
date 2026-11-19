/**
 * Accordion: buttons toggle labelled regions. One open at a time unless
 * the root has data-accordion-multiple.
 */
((Drupal, once) => {
  Drupal.behaviors.impeccabyteAccordion = {
    attach(context) {
      once('ib-accordion', '[data-accordion]', context).forEach((root) => {
        const multiple = root.hasAttribute('data-accordion-multiple');
        const triggers = Array.from(root.querySelectorAll('.accordion__trigger'));
        const set = (trigger, open) => {
          trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
          const panel = root.querySelector(`#${trigger.getAttribute('aria-controls')}`);
          if (panel) panel.hidden = !open;
        };
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
