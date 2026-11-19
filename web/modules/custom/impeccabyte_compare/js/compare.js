/**
 * Device comparison.
 *
 * Up to three devices are held in sessionStorage. Toggles on device cards
 * add and remove them; the tray lists them and opens the comparison page.
 */
((Drupal, once) => {
  const KEY = 'impeccabyte.compare';
  const MAX = 3;

  const read = () => {
    try {
      const items = JSON.parse(window.sessionStorage.getItem(KEY) || '[]');
      return Array.isArray(items) ? items.filter((item) => item && item.id).slice(0, MAX) : [];
    }
    catch (e) {
      return [];
    }
  };
  const write = (items) => {
    try { window.sessionStorage.setItem(KEY, JSON.stringify(items.slice(0, MAX))); }
    catch (e) { /* Storage can be unavailable in private modes; the page still works. */ }
  };

  const render = () => {
    const items = read();
    const ids = items.map((item) => String(item.id));
    const full = items.length >= MAX;

    document.querySelectorAll('[data-compare-toggle]').forEach((toggle) => {
      const on = ids.includes(String(toggle.dataset.compareId));
      toggle.setAttribute('aria-pressed', on ? 'true' : 'false');
      toggle.disabled = !on && full;
      const label = toggle.querySelector('[data-compare-label]');
      if (label) label.textContent = on ? label.dataset.labelOn : label.dataset.labelOff;
    });

    document.querySelectorAll('[data-compare-tray]').forEach((tray) => {
      const max = Number(tray.dataset.compareMax) || MAX;
      const count = tray.querySelector('[data-compare-count]');
      if (count) count.textContent = count.dataset.template.replace('@count', items.length).replace('@max', max);
      const slots = tray.querySelector('[data-compare-slots]');
      if (slots) {
        slots.replaceChildren();
        for (let i = 0; i < max; i += 1) {
          const li = document.createElement('li');
          const item = items[i];
          if (item) {
            li.className = 'compare-tray__slot';
            const name = document.createElement('span');
            name.textContent = item.name;
            const remove = document.createElement('button');
            remove.type = 'button';
            remove.className = 'compare-tray__remove';
            remove.setAttribute('aria-label', slots.dataset.removeLabel.replace('@name', item.name));
            remove.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>';
            remove.addEventListener('click', () => {
              write(read().filter((other) => String(other.id) !== String(item.id)));
              render();
              // Focus stays in the tray after the removed chip disappears.
              (slots.querySelector('.compare-tray__remove') || tray.querySelector('[data-compare-go]'))?.focus();
            });
            li.append(name, remove);
          }
          else {
            li.className = 'compare-tray__slot compare-tray__slot--empty';
            li.textContent = slots.dataset.emptyLabel;
          }
          slots.append(li);
        }
      }
      const go = tray.querySelector('[data-compare-go]');
      if (go) go.disabled = items.length < 2;
    });
  };

  Drupal.behaviors.impeccabyteCompare = {
    attach(context) {
      once('ib-compare-toggle', '[data-compare-toggle]', context).forEach((toggle) => {
        toggle.addEventListener('click', () => {
          const items = read();
          const id = String(toggle.dataset.compareId);
          const on = items.some((item) => String(item.id) === id);
          if (on) write(items.filter((item) => String(item.id) !== id));
          else if (items.length < MAX) write(items.concat([{ id, name: toggle.dataset.compareName }]));
          render();
        });
      });
      once('ib-compare-tray', '[data-compare-tray]', context).forEach((tray) => {
        tray.querySelector('[data-compare-go]')?.addEventListener('click', () => {
          const ids = read().map((item) => item.id);
          if (ids.length >= 2) window.location.href = `${tray.dataset.compareUrl}?ids=${ids.join(',')}`;
        });
      });
      render();
    },
  };
})(Drupal, once);
