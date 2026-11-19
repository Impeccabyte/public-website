/**
 * Choice cards: a tablist. Click or arrow keys select a card and show its panel.
 */
((Drupal, once) => {
  Drupal.behaviors.impeccabyteChoice = {
    attach(context) {
      once('ib-choice', '[data-choice]', context).forEach((root) => {
        const tabs = Array.from(root.querySelectorAll('[role="tab"]'));
        const select = (tab, focus) => {
          tabs.forEach((t) => {
            const on = t === tab;
            t.setAttribute('aria-selected', on ? 'true' : 'false');
            t.tabIndex = on ? 0 : -1;
            const panel = root.querySelector(`#${t.getAttribute('aria-controls')}`);
            if (panel) panel.hidden = !on;
          });
          if (focus) tab.focus();
        };
        tabs.forEach((tab, i) => {
          tab.addEventListener('click', () => select(tab, false));
          tab.addEventListener('keydown', (e) => {
            const move = { ArrowRight: 1, ArrowDown: 1, ArrowLeft: -1, ArrowUp: -1 }[e.key];
            if (move) { e.preventDefault(); select(tabs[(i + move + tabs.length) % tabs.length], true); }
          });
        });
      });
    },
  };
})(Drupal, once);
