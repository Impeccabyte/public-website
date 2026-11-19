/**
 * Gallery: thumbnail buttons switch the main image.
 */
((Drupal, once) => {
  Drupal.behaviors.impeccabyteGallery = {
    attach(context) {
      once('ib-gallery', '[data-gallery]', context).forEach((root) => {
        const thumbs = Array.from(root.querySelectorAll('.gallery__thumb'));
        thumbs.forEach((thumb) => {
          thumb.addEventListener('click', () => {
            thumbs.forEach((other) => {
              const on = other === thumb;
              other.setAttribute('aria-pressed', on ? 'true' : 'false');
              const view = root.querySelector(`#${other.getAttribute('aria-controls')}`);
              if (view) view.hidden = !on;
            });
          });
        });
      });
    },
  };
})(Drupal, once);
