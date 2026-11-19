/**
 * Tabs: one panel shown at a time, arrow keys move between tabs.
 */
((Drupal, once) => {
  Drupal.behaviors.impeccabyteTabs = {
    attach(context) {
      once('ib-tabs', '[data-tabs]', context).forEach((root) => {
        const tabs = Array.from(root.querySelectorAll(':scope > .tabs__list > [role="tab"]'));
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
            const move = { ArrowRight: 1, ArrowLeft: -1 }[e.key];
            if (move) { e.preventDefault(); select(tabs[(i + move + tabs.length) % tabs.length], true); }
            if (e.key === 'Home') { e.preventDefault(); select(tabs[0], true); }
            if (e.key === 'End') { e.preventDefault(); select(tabs[tabs.length - 1], true); }
          });
        });
      });
    },
  };
})(Drupal, once);
