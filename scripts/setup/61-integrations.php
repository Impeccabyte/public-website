<?php

/**
 * @file
 * Section changes for the Integrations page: the feature grid and the image
 * and text section gain an anchor, so links on the page can jump to them,
 * as the logo strip and bundle already can.
 *
 * Safe to re-run. Run it with
 *   ./scripts/drush php:script scripts/setup/61-integrations.php
 * then export config.
 */

require_once __DIR__ . '/_helpers.php';

$p = 'paragraph';
ib_field($p, 'feature_grid', 'field_anchor', 'string', 'Anchor', ['weight' => 20, 'description' => 'Lets links jump to this section, for example kinds.']);
ib_field($p, 'media_split', 'field_anchor', 'string', 'Anchor', ['weight' => 20, 'description' => 'Lets links jump to this section, for example your-pos.']);

print "Integrations sections are in place.\n";
