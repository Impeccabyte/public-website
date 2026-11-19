<?php

/**
 * @file
 * Section changes for the Online page: the logo strip gains a heading, an
 * intro, a note and a "tiles" layout, used for the gateway logos.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/setup/53-online.php
 * then export config.
 */

require_once __DIR__ . '/_helpers.php';

$p = 'paragraph';
ib_field($p, 'logo_strip', 'field_layout', 'list_string', 'Layout', [
  'storage' => ['allowed_values' => ['strip' => 'One row under a short intro', 'tiles' => 'Logo tiles under a heading']],
  'required' => TRUE,
  'default' => 'strip',
  'widget' => 'options_select',
  'weight' => -1,
]);
ib_field($p, 'logo_strip', 'field_eyebrow', 'string', 'Eyebrow', ['weight' => 3, 'description' => 'Tiles layout only.']);
ib_field($p, 'logo_strip', 'field_heading', 'string', 'Heading', ['weight' => 4, 'description' => 'Tiles layout only.']);
ib_field($p, 'logo_strip', 'field_body', 'string_long', 'Body', ['weight' => 5, 'description' => 'Tiles layout only. Shown under the heading.', 'widget_settings' => ['rows' => 3]]);
ib_field($p, 'logo_strip', 'field_title', 'string', 'Last tile title', ['weight' => 6, 'description' => 'Tiles layout only. A text tile after the logos, such as "Using something else?"']);
ib_field($p, 'logo_strip', 'field_note', 'string', 'Last tile text', ['weight' => 7, 'description' => 'Tiles layout only.']);
ib_field($p, 'logo_strip', 'field_footnote', 'string', 'Footnote', ['weight' => 8, 'description' => 'Tiles layout only. Small print under the tiles.']);
ib_field($p, 'logo_strip', 'field_anchor', 'string', 'Anchor', ['weight' => 9, 'description' => 'Lets links jump to this section, for example gateways.']);

print "Online sections are in place.\n";
