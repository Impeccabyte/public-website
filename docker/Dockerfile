# Drupal 11 on PHP 8.4 with Apache. Drupal core lives in /opt/drupal in this image.
# Drupal 12 is due the week of 7 December 2026; stay on 11 until contrib modules catch up.
FROM drupal:11-php8.4-apache

RUN apt-get update \
 && apt-get install -y --no-install-recommends git unzip mariadb-client \
 && rm -rf /var/lib/apt/lists/*

WORKDIR /opt/drupal

# Core tooling and the modules the build brief relies on.
RUN composer require --no-interaction --no-progress \
      drush/drush \
      drupal/paragraphs \
      drupal/entity_reference_revisions \
      drupal/webform \
      drupal/pathauto \
      drupal/metatag \
      drupal/admin_toolbar \
      drupal/twig_tweak

# Catalog search and filtering. Kept in a second layer so a version conflict
# here is easy to spot and does not hide the layer above.
RUN composer require --no-interaction --no-progress \
      drupal/search_api \
      drupal/facets \
      drupal/better_exposed_filters \
      drupal/svg_image \
      drupal/simple_sitemap \
      drupal/redirect

COPY docker/php.ini /usr/local/etc/php/conf.d/zz-impeccabyte.ini

ENV PATH="${PATH}:/opt/drupal/vendor/bin"
