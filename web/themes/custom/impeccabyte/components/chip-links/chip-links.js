/**
 * Industry picker: one chip pressed at a time, each showing its own panel.
 */
((Drupal, once) => {
  Drupal.behaviors.impeccabyteChipLinks = {
    attach(context) {
      once('ib-chip-links', '[data-chip-links]', context).forEach((root) => {
        const chips = Array.from(root.querySelectorAll('.chip[aria-controls]'));
        chips.forEach((chip) => {
          chip.addEventListener('click', () => {
            chips.forEach((other) => {
              const on = other === chip;
              other.setAttribute('aria-pressed', on ? 'true' : 'false');
              const panel = root.querySelector(`#${other.getAttribute('aria-controls')}`);
              if (panel) panel.hidden = !on;
            });
          });
        });
      });
    },
  };
})(Drupal, once);
