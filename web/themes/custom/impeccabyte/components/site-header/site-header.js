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
