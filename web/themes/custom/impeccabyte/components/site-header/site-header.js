/**
 * Site header: mega menus and the mobile drawer.
 *
 * Menus open on click, close on Escape (focus returns to the button), on a
 * click outside and when focus leaves the header.
 */
((Drupal, once) => {
  Drupal.behaviors.impeccabyteHeader = {
    attach(context) {
      once('ib-header', '[data-site-header]', context).forEach((header) => {
        const toggles = Array.from(header.querySelectorAll('[data-mega-toggle]'));
        const burger = header.querySelector('[data-drawer-toggle]');
        const drawer = header.querySelector('[data-drawer]');
        const label = header.querySelector('[data-drawer-label]');
        const panelOf = (toggle) => header.querySelector(`#${toggle.getAttribute('aria-controls')}`);

        const closeMegas = (except) => {
          toggles.forEach((t) => {
            if (t === except) return;
            t.setAttribute('aria-expanded', 'false');
            panelOf(t)?.classList.remove('is-open');
          });
        };
        const setDrawer = (open) => {
          if (!burger || !drawer) return;
          burger.setAttribute('aria-expanded', open ? 'true' : 'false');
          drawer.classList.toggle('is-open', open);
          if (label) label.textContent = open ? label.dataset.closeLabel : label.dataset.openLabel;
        };

        toggles.forEach((toggle) => {
          toggle.addEventListener('click', () => {
            const open = toggle.getAttribute('aria-expanded') !== 'true';
            closeMegas(toggle);
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            panelOf(toggle)?.classList.toggle('is-open', open);
          });
        });
        burger?.addEventListener('click', () => setDrawer(burger.getAttribute('aria-expanded') !== 'true'));

        // Business lines inside a mega menu (only rendered once there are
        // two): a vertical tab list. Click, arrow keys, Home and End switch
        // lines; hovering switches after a short pause, so a diagonal move
        // toward the columns does not flip lines on the way.
        header.querySelectorAll('[data-mega-rail]').forEach((rail) => {
          const tabs = Array.from(rail.querySelectorAll('[role="tab"]'));
          const select = (tab, focus = false) => {
            tabs.forEach((t) => {
              const on = t === tab;
              t.setAttribute('aria-selected', on ? 'true' : 'false');
              t.tabIndex = on ? 0 : -1;
              const panel = header.querySelector(`#${t.getAttribute('aria-controls')}`);
              if (panel) panel.hidden = !on;
            });
            if (focus) tab.focus();
          };
          let hover;
          tabs.forEach((tab, i) => {
            tab.addEventListener('click', () => select(tab));
            tab.addEventListener('mouseenter', () => { hover = window.setTimeout(() => select(tab), 150); });
            tab.addEventListener('mouseleave', () => window.clearTimeout(hover));
            tab.addEventListener('keydown', (e) => {
              const next = { ArrowDown: i + 1, ArrowUp: i - 1, Home: 0, End: tabs.length - 1 }[e.key];
              if (next === undefined) return;
              e.preventDefault();
              select(tabs[(next + tabs.length) % tabs.length], true);
            });
          });
        });

        header.addEventListener('keydown', (e) => {
          if (e.key !== 'Escape') return;
          const openToggle = toggles.find((t) => t.getAttribute('aria-expanded') === 'true');
          if (openToggle) { closeMegas(); openToggle.focus(); }
          else if (burger?.getAttribute('aria-expanded') === 'true') { setDrawer(false); burger.focus(); }
        });
        header.addEventListener('focusout', (e) => {
          if (e.relatedTarget && !header.contains(e.relatedTarget)) { closeMegas(); setDrawer(false); }
        });
        document.addEventListener('click', (e) => {
          if (!header.contains(e.target)) { closeMegas(); }
        });
        // Leaving the narrow layout closes the drawer so it cannot stay open unseen.
        window.matchMedia('(min-width: 1181px)').addEventListener('change', (e) => { if (e.matches) setDrawer(false); else closeMegas(); });
      });
    },
  };
})(Drupal, once);
