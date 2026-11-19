/**
 * Catalog helpers: the Filters disclosure on small screens and "show more".
 */
((Drupal, once) => {
  Drupal.behaviors.impeccabyteCatalog = {
    attach(context) {
      once('ib-filters-toggle', '[data-catalog-filters-toggle]', context).forEach((button) => {
        const rail = document.getElementById(button.getAttribute('aria-controls'));
        if (!rail) return;
        // Stay open after a filter reloads the page on a small screen.
        let open = false;
        try { open = window.sessionStorage.getItem('impeccabyte.filtersOpen') === '1'; } catch (e) { /* ignore */ }
        const set = (state) => {
          button.setAttribute('aria-expanded', state ? 'true' : 'false');
          rail.classList.toggle('is-open', state);
          try { window.sessionStorage.setItem('impeccabyte.filtersOpen', state ? '1' : '0'); } catch (e) { /* ignore */ }
        };
        set(open);
        button.addEventListener('click', () => set(button.getAttribute('aria-expanded') !== 'true'));
      });

      // Show a page of cards at a time. Without JavaScript every card shows.
      once('ib-show-more', '[data-show-more]', context).forEach((list) => {
        const step = Number(list.dataset.showMore) || 12;
        const items = Array.from(list.children);
        const control = list.parentElement.querySelector('[data-show-more-control]');
        if (!control || items.length <= step) return;
        let shown = step;
        const apply = () => {
          items.forEach((item, i) => { item.hidden = i >= shown; });
          control.hidden = shown >= items.length;
        };
        control.querySelector('button').addEventListener('click', () => {
          const first = items[shown];
          shown += step;
          apply();
          // Move focus to the first newly shown card's link.
          first?.querySelector('a, button')?.focus();
        });
        apply();
      });
    },
  };
})(Drupal, once);
