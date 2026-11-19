/**
 * Savings estimator: recalculates the table as the slider moves.
 *
 * Flat rate and interchange plus are a percentage of volume plus a per-sale
 * fee; the number of sales is volume divided by the average ticket.
 */
((Drupal, once) => {
  const money = (n) => `$${Math.round(n).toLocaleString('en-US')}`;
  Drupal.behaviors.impeccabyteEstimator = {
    attach(context) {
      once('ib-estimator', '[data-estimator]', context).forEach((root) => {
        const input = document.getElementById(root.dataset.input);
        if (!input) return;
        const d = root.dataset;
        const output = document.querySelector(`output[for="${input.id}"]`);
        const set = (key, value) => { const el = root.querySelector(`[data-estimator-${key}]`); if (el) el.textContent = value; };
        const update = () => {
          const volume = Number(input.value);
          const sales = volume / Number(d.ticket);
          const flat = volume * Number(d.flatPercent) / 100 + sales * Number(d.flatCents) / 100;
          const icp = volume * Number(d.icPercent) / 100 + sales * Number(d.icCents) / 100;
          if (output) output.textContent = money(volume);
          input.setAttribute('aria-valuetext', money(volume));
          set('flat', money(flat));
          set('flat-year', money(flat * 12));
          set('icp', money(icp));
          set('icp-year', money(icp * 12));
          set('save', money((flat - Number(d.fee)) * 12));
        };
        input.addEventListener('input', update);
        update();
      });
    },
  };
})(Drupal, once);
