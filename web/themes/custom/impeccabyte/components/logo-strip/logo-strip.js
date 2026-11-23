/**
 * @file
 * Starts the slow scroll on a logo strip with many logos, with a pause
 * button (WCAG 2.2.2). Leaves the strip still when the visitor prefers
 * reduced motion.
 */
(function (Drupal, once) {
  Drupal.behaviors.impeccabyteLogoStrip = {
    attach(context) {
      once('logo-strip', '[data-logo-strip]', context).forEach((strip) => {
        const toggle = strip.querySelector('[data-logo-strip-toggle]');
        if (!toggle || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
          return;
        }
        const pauseIcon = toggle.querySelector('.logo-strip__toggle-pause');
        const playIcon = toggle.querySelector('.logo-strip__toggle-play');
        const setPaused = (paused) => {
          strip.classList.toggle('is-paused', paused);
          pauseIcon.hidden = paused;
          playIcon.hidden = !paused;
          toggle.setAttribute('aria-label', paused ? Drupal.t('Play the scrolling logos') : Drupal.t('Pause the scrolling logos'));
        };
        toggle.addEventListener('click', () => setPaused(!strip.classList.contains('is-paused')));
        strip.classList.add('is-playing');
        toggle.hidden = false;
        setPaused(false);
      });
    },
  };
})(Drupal, once);
